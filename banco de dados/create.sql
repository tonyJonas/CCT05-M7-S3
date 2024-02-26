CREATE TABLE tarefas (
    id_tarefa SERIAL PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    responsavel VARCHAR(255) NOT NULL,
    data_criacao DATE NOT NULL,
    prioridade INT CHECK (dificuldade BETWEEN 1 AND 10),
    descricao TEXT,
    status VARCHAR(50) DEFAULT 'TO DO'
);
