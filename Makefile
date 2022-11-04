share:
	ngrok http 80 --region=eu --host-header=tgatubot.loc

dev:
	docker compose up -d -f docker.compose.dev.yml

prod:
	docker compose up -d -f docker.compose.prod.yml
