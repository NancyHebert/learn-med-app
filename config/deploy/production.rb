set :stage, :production

set :server_name, 'learn.med.uottawa.ca'

server "#{fetch :server_name}", user: 'deploy', roles: %w{web app db}

set :wpcli_remote_url, "https://#{fetch :server_name}/"

set :ssh_options, {
  forward_agent: true
}

# Prevent pushing the local database to production

Rake::Task["wpcli:db:push"].clear
Rake::Task["wpcli:db:set_env_settings_remote"].clear

namespace :wpcli do
  namespace :db do

    task :push do
      puts "Cancelled. Can't push the local db to #{fetch :stage}"
    end

    task :set_env_settings_remote do
      puts "Cancelled. Can't push the env settings to #{fetch :stage}"
    end
  end
end
