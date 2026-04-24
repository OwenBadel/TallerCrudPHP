# TallerCrudPHP

## Configuracion rapida

1. Crear base de datos y tabla ejecutando el script:

	mysql -u root -p < database/schema.sql

2. Ajustar credenciales por variables de entorno:

	DB_HOST=127.0.0.1
	DB_PORT=3306
	DB_NAME=crud_usuarios
	DB_USER=root
	DB_PASS=

3. Levantar servidor web apuntando a la carpeta public/.