share:
	ngrok http 80 --region=eu --host-header=tgatubot.loc

build:
	docker compose up -d --build
