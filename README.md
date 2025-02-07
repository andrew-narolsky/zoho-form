# Zoho Form

## Installation (production)

### Create non-root User

1. Create user and set his password
```
sudo adduser laravel --disabled-password
```

2. Add root privileges for user
```
sudo usermod -aG sudo laravel
```

3. Copy access key to laravel .ssh directory
```
sudo mkdir /home/laravel/.ssh
sudo chown laravel:laravel /home/laravel/.ssh
sudo cp .ssh/authorized_keys /home/laravel/.ssh/authorized_keys
sudo chown laravel:laravel /home/laravel/.ssh/authorized_keys
```

4. Remove password prompt for laravel user (add at the end of file)
```
sudo visudo
----
laravel ALL=(ALL) NOPASSWD: ALL
```

5. Login as laravel user and edit (add at the top):
```
nano ~/.bashrc
----
cd ~/zoho-form/
export LC_ALL="en_US.UTF-8"
export LC_CTYPE="en_US.UTF-8"
```

6. Change DNS servers to Google
```
sudo sed -i -r 's/dns-nameservers[\ 0-9\.]+/dns-nameservers 8.8.8.8 8.8.4.4/g' /etc/network/interfaces.d/50-cloud-init.cfg
```

### Add swap file ([source](https://www.digitalocean.com/community/tutorials/how-to-add-swap-on-ubuntu-14-04))

1. Create a 4 Gigabyte file, enabling the swap file, set up the swap space
```
sudo fallocate -l 4G /swapfile
sudo chmod 600 /swapfile
sudo mkswap /swapfile
sudo swapon /swapfile
```

2. Make the swap file permanent (add at the end of file)
```
sudo nano /etc/fstab
----
/swapfile   none    swap    sw    0   0
```

3. Tweak your swap settings
```
sudo sysctl vm.swappiness=10
sudo sysctl vm.vfs_cache_pressure=50
```

4. Make changes permanent (add at the end of file)
```
sudo nano /etc/sysctl.conf
----
vm.swappiness=10
vm.vfs_cache_pressure = 50
```

### Install Docker and Docker-compose

1. Update packages database
```
sudo apt update
```

2. Add GPG key from official docker repository and add docker repository
```
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -
sudo add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu bionic stable"
```

3. Update packages database
```
sudo apt update
```

4. Install docker
```
sudo apt install docker-ce
```

5. Add current user to docker group
```
sudo usermod -aG docker ${USER}
```

6. Restart ssh session

7. Download docker compose
```
sudo curl -L https://github.com/docker/compose/releases/download/v2.0.1/docker-compose-`uname -s`-`uname -m` -o /usr/local/bin/docker-compose
```

8. Next we'll set the permissions
```
sudo chmod +x /usr/local/bin/docker-compose
```

### Clone this repository

1. Init git settings
```
git config --global alias.up '!git remote update -p; git merge --ff-only @{u}'
```

2. Clone repository
```
git clone https://github.com/andrew-narolsky/zoho-form.git
```

### Configure laravel and docker

1. Copy laravel `.env.example` file to `.env` and change it's variables
```
cp ~/zoho-form/.env.example ~/zoho-form/.env
```

2. Copy docker `docker/env-production` to `docker/.env` and change it's variables
```
cp ~/zoho-form/docker/env-production ~/zoho-form/docker/.env
```

3. Copy `docker/nginx/sites/site.conf.example-dev` to `docker/nginx/sites/site.conf` and change example.com to current domain
```
cp ~/zoho-form/docker/nginx/sites/site.conf.example-dev ~/zoho-form/docker/nginx/sites/site.conf
```

4. Start docker containers
```
cd ~/zoho-form/docker
docker-compose up -d
```

5. Add executing of containers and the startup
```
sudo crontab -e
---
# Start docker-compose at boot
@reboot cd /home/laravel/zoho-form/docker/ && /usr/local/bin/docker-compose up -d

#Logs rotate
@weekly /home/laravel/zoho-form/logs_rotate.sh > /dev/null 2>&1
```

### Init laravel

1. Create FPC storage directory
```
mkdir /home/laravel/zoho-form/storage/page-cache
```

2. Enter workspace container
```
cd /home/laravel/zoho-form/docker/ 
docker-compose exec workspace bash
```

3. Install composer and nmp packages
```
composer install
npm install
```

4. Compile css and js files
```
npm run production
```

5. Create database
```
php artisan migrate
```

6. Restore site dump from backup and change admin password

### Configure SSL from Let's Encrypt ([source](https://www.digitalocean.com/community/tutorials/how-to-secure-nginx-with-let-s-encrypt-on-ubuntu-14-04))

1. Install Let's Encrypt Client
```
sudo apt-get install letsencrypt
```

2. Get SSL certificates (change example.com to current domain)
```
sudo certbot certonly -a webroot --webroot-path=/home/laravel/zoho-form/public/ -d EXAMPLE.com -d www.EXAMPLE.com
```

3. Generate Strong Diffie-Hellman Group
```
sudo openssl dhparam -out /home/laravel/zoho-form/letsencrypt/dhparam.pem 2048
```

4. Copy certificate files to local directory (change example.com to current domain)
```
sudo cp /etc/letsencrypt/live/EXAMPLE.com/fullchain.pem /home/laravel/zoho-form/letsencrypt/fullchain.pem
sudo cp /etc/letsencrypt/live/EXAMPLE.com/privkey.pem /home/laravel/zoho-form/letsencrypt/privkey.pem
```

5. Copy settings from `docker/nginx/sites/site.conf.example-prod-ssl` to `docker/nginx/sites/site.conf` and change `example.com` to current domain name

6. Copy settings from `docker/nginx/sites/mail.conf.example` to `docker/nginx/sites/mail.conf` and change `EXAMPLE.com` to current domain name

7. Restart nginx container
```
cd /home/laravel/zoho-form/docker/ 
docker-compose restart
```

8. Set up auto renewal (change example.com to current domain)
```
sudo crontab -e
---
# Copy certificate files to local directory
49 2 * * 1 cp /etc/letsencrypt/live/EXAMPLE.com/fullchain.pem /home/laravel/zoho-form/letsencrypt/fullchain.pem
49 2 * * 1 cp /etc/letsencrypt/live/EXAMPLE.com/privkey.pem /home/laravel/zoho-form/letsencrypt/privkey.pem

# Restart nginx
50 2 * * 1 cd /home/laravel/zoho-form/docker/ && /usr/local/bin/docker-compose restart nginx
```

9. In case of errors of certbot update you can use `--no-self-upgrade` flag:
```
certbot-auto --no-self-upgrade renew
```
