set :application, 'learn.med'
set :repo_name, 'E-Learning/learn-med-app'
set :repo_url, -> {"git@git.med.uottawa.ca:#{fetch(:repo_name)}.git"}

# Branch options
# Prompts for the branch name (defaults to current branch)
#ask :branch, proc { `git rev-parse --abbrev-ref HEAD`.chomp }

# Sets branch to current one
#set :branch, proc { `git rev-parse --abbrev-ref HEAD`.chomp }

# Hardcodes branch to always be master
# This could be overridden in a stage config file
set :branch, "master"

set :deploy_to, "/var/www/html/#{fetch(:application)}"

set :log_level, :info

set :linked_files, %w{.env}
set :linked_dirs, %w{web/app/uploads}

# WP-CLI
server '127.0.0.1', user: 'vagrant', keys: ['../learn-med-stack/.vagrant/machines/default/virtualbox/private_key'], forward_agent: true, port: 2222, roles: %w{dev}, no_release: true
set :dev_path, '/srv/www/learn.med/current/'
set :wpcli_local_url, 'https://learn.med.uottawa.dev/'
set :local_tmp_dir, '.'
set :composer_roles, :app

set :wpcli_dev_backup_db_file, -> {"#{fetch(:local_tmp_dir)}/dev.sql"}

# Slackistrano
set :slack_webhook, "https://hooks.slack.com/services/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"

set :slack_run_updating, ->{ false }
set :slack_run_updated,  ->{ true  }
set :slack_run_failed,   ->{ false }

set :slack_msg_updated, ->{ "*#{ENV['SLACK_USER'] || ENV['USER'] || ENV['USERNAME']}* has deployed branch `#{fetch :branch}` of #{fetch :repo_name} to `#{fetch :stage, 'production'}`." }

namespace :deploy do

  desc 'Restart application'
  task :restart do
    on roles(:app), in: :sequence, wait: 5 do
      # This task is required by Capistrano but can be a no-op
      # Your restart mechanism here, for example:
      # execute :service, :nginx, :reload
    end
  end

end
