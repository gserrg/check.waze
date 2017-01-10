#!/usr/bin/ruby
# encoding: utf-8
#
# scan_segments.rb
# Popula tabelas em uma base PostgreSQL com os dados dos segmentos de uma região.
# (c)2015 Eduardo Garcia <edulg72@gmail.com>
#
# Usage:
# scan_segments.rb <usuario> <senha> <longitude oeste> <latitude norte> <longitude leste> <latitude sul> <passo em graus*>
#
# * Define o tamanho dos quadrados das áreas para análise. Em regiões muito populosas usar valore pequenos para não sobrecarregar o server.

require 'mechanize'
require 'pg'
require 'yaml'
require 'json'

if ARGV.size < 5
  puts "Usage: ruby scan_segments.rb <user> <password> <west longitude> <north latitude> <east longitude> <south latitude> <step>"
  exit
end

LongOeste = ARGV[0].to_f
LatNorte = ARGV[1].to_f
LongLeste = ARGV[2].to_f
LatSul = ARGV[3].to_f
Passo = ARGV[4].to_f

puts "Starting analysis on [#{LongOeste} #{LatNorte}] - [#{LongLeste} #{LatSul}]"

config = YAML::load_file('../config/scanner.yaml')
agent = Mechanize.new
agent.user_agent = "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.75 Safari/537.36"
begin
  page = agent.get "https://www.waze.com/row-Descartes-live/app/Session"
rescue Mechanize::ResponseCodeError
  csrf_token = agent.cookie_jar.jar['www.waze.com']['/']['_csrf_token'].value
end
login = agent.post('https://www.waze.com/login/create', config['editor'], {"X-CSRF-Token" => csrf_token})

db = PG::Connection.new(config['db'])

db.exec_params('delete from streets where id in (select street_id from segments where longitude between $1 and $2 and latitude between $3 and $4)',[LongOeste,LongLeste,LatSul,LatNorte])
db.exec_params('delete from segments where longitude between $1 and $2 and latitude between $3 and $4',[LongOeste,LongLeste,LatSul,LatNorte])
db.exec('vacuum streets')
db.exec('vacuum segments')

@users = {}
@countries = {}
@states = {}
@cities = {}
@streets = {}
@segments = {}

def busca(db,agent,longOeste,latNorte,longLeste,latSul,passo,exec)
  lonIni = longOeste
  while lonIni < longLeste do
    lonFim = [(lonIni + passo).round(13) , longLeste].min
    lonFim = lonIni + passo if (lonFim - lonIni) < (passo / 2)
    latIni = latNorte
    while latIni > latSul do
      latFim = [(latIni - passo).round(13), latSul].max
      latFim = latIni - passo if (latIni - latFim) < (passo / 2)
      area = [lonIni, latIni, lonFim, latFim]

      begin
        wme = agent.get "https://www.waze.com/row-Descartes-live/app/Features?roadTypes=1%2C2%2C3%2C4%2C5%2C6%2C7%2C8%2C10%2C15%2C16%2C17%2C18%2C19%2C20&zoom=5&bbox=#{area.join('%2C')}"

        json = JSON.parse(wme.body)
        #puts "#{json}"

        # Get users data
        json['users']['objects'].each {|u| @users[u['id']] = "#{u['id']},\"#{u['userName'].nil? ? u['userName'] : u['userName'][0..49]}\",#{u['rank']+1}\n" if not @users.has_key?(u['id']) }

        # Get countries names
        json['countries']['objects'].each {|c| @countries[c['id']] = "#{c['id']},\"#{c['name'].nil? ? c['name'] : c['name'][0..49]}\"\n" if not @countries.has_key?(c['id']) }

        # Get state names
        json['states']['objects'].each {|s| @states[s['id']] = "#{s['id']},\"#{s['name'].nil? ? s['name'] : s['name'][0..49]}\",#{s['countryID']}\n" if not @states.has_key?(s['id']) }

        # Get city names
        json['cities']['objects'].each {|c| @cities[c['id']] = "#{c['id']},\"#{c['name'].nil? ? c['name'] : c['name'][0..99]}\",#{c['stateID']},#{c['isEmpty'] ? 'TRUE':'FALSE'},#{c['countryID']}\n" if not @cities.has_key?(c['id']) }

        # Get street names
        json['streets']['objects'].each {|s| @streets[s['id']] = "#{s['id']},\"#{s['name'].nil? ? s['name'] : s['name'][0..99]}\",#{s['cityID']},#{s['isEmpty'] ? 'TRUE' : 'FALSE' }\n" if not @streets.has_key?(s['id']) }

        # Get segments data
        json['segments']['objects'].each do |s|
          (longitude, latitude) = s['geometry']['coordinates'][(s['geometry']['coordinates'].size / 2)]
          connection = json['connections']["#{s['id']}f"] || json['connections']["#{s['id']}r"]
          @segments[s['id']] = "#{s['id']},#{longitude},#{latitude},#{s['roadType']},#{s['level']},#{(s['lockRank'].nil? ? s['lockRank'] : s['lockRank'] + 1)},#{(s['updatedOn'].nil? ? s['createdBy'] : s['updatedBy'])},#{(s['updatedOn'].nil? ? Time.at(s['createdOn']/1000) : Time.at(s['updatedOn']/1000))},#{s['primaryStreetID']},#{s['length']},#{connection ? 'TRUE' : 'FALSE' },#{s['fwdDirection']},#{s['revDirection']},#{s['fwdMaxSpeed']},#{s['revMaxSpeed']},#{(s.has_key?('fwdMaxSpeedUnverified') ? s['fwdMaxSpeedUnverified'] : 'FALSE' )},#{(s.has_key?('revMaxSpeedUnverified') ? s['revMaxSpeedUnverified'] : 'FALSE' )},#{(s['junctionID'].nil? ? 'FALSE' : 'TRUE')},#{s['streetIDs'].size > 0}\n" if not @segments.has_key?(s['id'])
        end

      rescue Mechanize::ResponseCodeError, NoMethodError
        # If issue is related to json package size, divide the area by 4 (limited to 3 divisions)
        if exec < 3
          busca(db,agent,area[0],area[1],area[2],area[3],(passo/2),(exec+1))
        else
          puts "[#{Time.now.strftime('%d/%m/%Y %H:%M:%S')}] - ResponseCodeError em #{area}"
        end
      rescue JSON::ParserError
        if exec < 3
          sleep(5)
          busca(db,agent,area[0],area[1],area[2],area[3],passo,(exec+1))
        else
          puts "Erro JSON em #{area}"
        end
      end

      latIni = latFim
    end
    lonIni = lonFim
  end
end

busca(db,agent,LongOeste,LatNorte,LongLeste,LatSul,Passo,1)

db.exec("delete from users where id in (#{@users.keys.join(',')})") if @users.size > 0
db.copy_data('COPY users (id,username,rank) FROM STDIN CSV') do
  @users.each_value {|u| db.put_copy_data u}
end
db.exec('vacuum users')

db.exec("delete from countries where id in (#{@countries.keys.join(',')})") if @countries.size > 0
db.copy_data('COPY countries (id,name) FROM STDIN CSV') do
  @countries.each_value {|s| db.put_copy_data s}
end
db.exec('vacuum states')

db.exec("delete from states where id in (#{@states.keys.join(',')})") if @states.size > 0
db.copy_data('COPY states (id,name,country_id) FROM STDIN CSV') do
  @states.each_value {|s| db.put_copy_data s}
end
db.exec('vacuum states')

db.exec("delete from cities where id in (#{@cities.keys.join(',')})") if @cities.size > 0
db.copy_data('COPY cities (id,name,state_id,isempty,country_id) FROM STDIN CSV') do
  @cities.each_value {|c| db.put_copy_data c}
end
db.exec('vacuum cities')

db.exec("delete from streets where id in (#{@streets.keys.join(',')})") if @streets.size > 0
db.copy_data('COPY streets (id,name,city_id,isempty) FROM STDIN CSV') do
  @streets.each_value {|s| db.put_copy_data s}
end
db.exec('vacuum streets')

db.exec("delete from segments where id in (#{@segments.keys.join(',')})") if @segments.size > 0
db.copy_data('COPY segments (id, longitude, latitude, roadtype, level, lock, last_edit_by, last_edit_on, street_id, length, connected, fwddirection, revdirection, fwdmaxspeed, revmaxspeed, fwdmaxspeedunverified, revmaxspeedunverified, roundabout, alt_names) FROM STDIN CSV') do
  @segments.each_value {|s| db.put_copy_data s}
end
db.exec('vacuum segments')
