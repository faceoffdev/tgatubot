share:
	ngrok http 80 --region=eu --host-header=tgatubot.loc

dev:
	docker compose -f docker.compose.dev.yml up -d app

prod:
	docker compose -f docker.compose.prod.yml up -d
