CREATE TABLE Users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at VARCHAR(19) DEFAULT TO_CHAR(CURRENT_TIMESTAMP, 'DD/MM/YYYY HH24:MI:SS')
);

CREATE TABLE Sessions (
    id SERIAL PRIMARY KEY,
    dateOpening VARCHAR(19) DEFAULT TO_CHAR(CURRENT_TIMESTAMP, 'DD/MM/YYYY HH24:MI:SS'),
    dateClosure VARCHAR(19),
	idUser INT NOT NULL,
	CONSTRAINT FK_idUser FOREIGN KEY (idUser) REFERENCES Users(id)
);