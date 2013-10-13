set :application, "trading_advantage"
set :repository, "git@github.com:jetthoughts/trading-advantage.git"

set :scm, :git # You can set :scm explicitly or Capistrano will make an intelligent guess based on known version control directory names

                                             #role :web, "your web-server here"                          # Your HTTP server, Apache/etc
role :app, "31.220.63.160", :primary => true # This may be the same as your `Web` server
                                             #role :db,  "your primary db-server here", :primary => true # This is where Rails migrations will run
                                             #role :db,  "your slave db-server here"
set :use_sudo, false
default_run_options[:pty]   = true
ssh_options[:forward_agent] = true
ssh_options[:port]          = 2212
set :deploy_to, "/data/apps/#{application}"
set :user, "rails"

# if you want to clean up old releases on each deploy uncomment this:
# after "deploy:restart", "deploy:cleanup"

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
end
