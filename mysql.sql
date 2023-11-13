CREATE DATABASE bd_chat;
USE bd_chat;

CREATE TABLE tbl_users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    pwd VARCHAR(255) NOT NULL,
    nom_real VARCHAR(100) NOT NULL
);

/* Lista de amistad para saber si las 2 personas son amigos */
CREATE TABLE tbl_listaAmistad (
    id_listaAmistad INT AUTO_INCREMENT PRIMARY KEY,
    id_user1 INT NOT NULL,
    id_user2 INT NOT NULL,
    status ENUM('pendiente', 'aceptado') NOT NULL
);

CREATE TABLE tbl_mensaje (
    id_mensaje INT AUTO_INCREMENT PRIMARY KEY,
    id_enviador INT NOT NULL,
    id_receptor INT NOT NULL,
    texto_mensaje TEXT NOT NULL,
    timestamp TIMESTAMP NOT NULL
);


/* La lista que ve el receptor */
CREATE TABLE tbl_listaSolicitud (
    id_solicitud INT AUTO_INCREMENT PRIMARY KEY,
    id_enviador INT NOT NULL,
    id_receptor INT NOT NULL,
    status ENUM('pendiente', 'aceptado', 'rechazado') NOT NULL
);

/* Relación entre la tabla "tbl_listaAmistad" y "tbl_users" */
ALTER TABLE tbl_listaAmistad
ADD FOREIGN KEY (id_user1) REFERENCES tbl_users(id_user),
ADD FOREIGN KEY (id_user2) REFERENCES tbl_users(id_user);

/* Relación entre la tabla "tbl_mensaje" y "tbl_users" */
ALTER TABLE tbl_mensaje
ADD FOREIGN KEY (id_enviador) REFERENCES tbl_users(id_user),
ADD FOREIGN KEY (id_receptor) REFERENCES tbl_users(id_user);

/* Relación entre la tabla "tbl_listaSolicitud" y "tbl_users" */
ALTER TABLE tbl_listaSolicitud
ADD FOREIGN KEY (id_enviador) REFERENCES tbl_users(id_user),
ADD FOREIGN KEY (id_receptor) REFERENCES tbl_users(id_user);