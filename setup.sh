snap install docker
sudo systemctl enable docker
sudo systemctl start docker
curl -s https://packages.cloud.google.com/apt/doc/apt-key.gpg | sudo apt-key add
sudo apt install make docker-compose -y
sudo make run