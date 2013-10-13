set :application, "trading_advantage"
set :repository, "git@github.com:jetthoughts/trading-advantage.git"

set :scm, :git # You can set :scm explicitly or Capistrano will make an intelligent guess based on known version control directory names

                                             #role :web, "your web-server here"                          # Your HTTP server, Apache/etc
role :app, "198.61.172.139", :primary => true # This may be the same as your `Web` server
                                             #role :db,  "your primary db-server here", :primary => true # This is where Rails migrations will run
                                             #role :db,  "your slave db-server here"
set :use_sudo, false
default_run_options[:pty]   = true
ssh_options[:forward_agent] = true
#ssh_options[:port]          = 2212
set :deploy_to, "/data/apps/#{application}"
#TODO: Need deploy from www-data. Ask for password
set :user, "root"
set :password, "ehD9ZC4HAsU2"

# if you want to clean up old releases on each deploy uncomment this:
after "deploy:restart", "deploy:copy_folders"

# if you're still using the script/reaper helper you will need
# these http://github.com/rails/irs_process_scripts

# If you are using Passenger mod_rails uncomment this:
# namespace :deploy do
#   task :start do ; end
#   task :stop do ; end
#   task :restart, :roles => :app, :except => { :no_release => true } do
#     run "#{try_sudo} touch #{File.join(current_path,'tmp','restart.txt')}"
#   end
# end
namespace :deploy do
  task :uname do
    run "uname -a"
  end

  desc 'sync folders with wordpress main project'
  task :copy_folders  do
    dest_path = '/var/www/vhosts/www.tradingadvantage.com/http_docs/wp-content'
    #run "#{try_sudo} cp -rf #{current_path}/wordpress/wp-content/* #{dest_path}/"
    %w(plugins themes).each do |folder_name|
      run "#{try_sudo} cp -rf #{current_path}/wordpress/wp-content/#{folder_name} #{dest_path}/"
      run "#{try_sudo} chown -R www-data:www-data #{dest_path}/#{folder_name}"
    end
  end
end
