all: help

help:
	@echo ""
	@echo "-- Help Menu"
	@echo ""
	@echo "   1. make run                - Start Container Use default setting (listen web on 8080 , ssh on 8022)"


run:
	@sh changeHost.sh
	@docker-compose up -d --build

clean:
	@sh kill.sh