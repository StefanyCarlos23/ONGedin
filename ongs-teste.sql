USE projeto_ongedin;

INSERT INTO usuario (nome, funcao, senha, data_cadastro) VALUES 
('Pequeno Cotolengo', 'A', 'senha1', NOW()),
('Passos da Criança', 'A', 'senha2', NOW()),
('Força Animal', 'A', 'senha3', NOW()),
('Um Lugar ao Sol', 'A', 'senha4', NOW()),
('Gerar', 'A', 'senha5', NOW()),
('TETO Brasil', 'A', 'senha6', NOW());

-- Inserindo administradores
INSERT INTO administrador (id_administrador) VALUES 
((SELECT id_usuario FROM usuario WHERE nome = 'Pequeno Cotolengo')),
((SELECT id_usuario FROM usuario WHERE nome = 'Passos da Criança')),
((SELECT id_usuario FROM usuario WHERE nome = 'Força Animal')),
((SELECT id_usuario FROM usuario WHERE nome = 'Um Lugar ao Sol')),
((SELECT id_usuario FROM usuario WHERE nome = 'Gerar')),
((SELECT id_usuario FROM usuario WHERE nome = 'TETO Brasil'));

-- Inserindo administrador_ong
INSERT INTO administrador_ong (id_admin_ong, area_atuacao, data_fundacao, endereco_rua, endereco_numero, endereco_complemento, endereco_bairro, endereco_cidade, endereco_estado, endereco_pais, endereco_cep) VALUES
((SELECT id_usuario FROM usuario WHERE nome = 'Pequeno Cotolengo'), 'Direitos Humanos', '1945-01-01', 'Rua A', '100', NULL, 'Centro', 'Curitiba', 'PR', 'Brasil', '80000-000'),
((SELECT id_usuario FROM usuario WHERE nome = 'Passos da Criança'), 'Educação', '2000-01-01', 'Rua B', '200', NULL, 'Centro', 'Curitiba', 'PR', 'Brasil', '80000-001'),
((SELECT id_usuario FROM usuario WHERE nome = 'Força Animal'), 'Defesa dos Animais', '2010-01-01', 'Rua C', '300', NULL, 'Centro', 'Curitiba', 'PR', 'Brasil', '80000-002'),
((SELECT id_usuario FROM usuario WHERE nome = 'Um Lugar ao Sol'), 'Educação', '2015-01-01', 'Rua D', '400', NULL, 'Centro', 'Curitiba', 'PR', 'Brasil', '80000-003'),
((SELECT id_usuario FROM usuario WHERE nome = 'Gerar'), 'Assistência Social', '2018-01-01', 'Rua E', '500', NULL, 'Centro', 'Curitiba', 'PR', 'Brasil', '80000-004'),
((SELECT id_usuario FROM usuario WHERE nome = 'TETO Brasil'), 'Desenvolvimento Econômico', '2020-01-01', 'Rua F', '600', NULL, 'Centro', 'Curitiba', 'PR', 'Brasil', '80000-005');

-- Inserindo perfis (com imagens como BLOB, aqui você pode usar um caminho ou outra lógica para inserir a imagem)
INSERT INTO perfil (descricao, foto) VALUES
('O Pequeno Cotolengo é uma ONG dedicada ao acolhimento de pessoas com deficiência intelectual. Desde 1945, promove a inclusão e oferece atendimento especializado, visando melhorar a qualidade de vida dos atendidos.', LOAD_FILE("images/pequeno-cotolengo.png")),
('A ONG Passos da Criança apoia crianças e adolescentes em vulnerabilidade social, oferecendo educação, saúde e atividades recreativas. Com foco no empoderamento e no fortalecimento de direitos, a organização busca transformar vidas e criar oportunidades para um futuro melhor.', LOAD_FILE("images/passos-da-crianca.png")),
('A ONG Força Animal protege animais abandonados e maltratados, oferecendo resgate, cuidados e abrigo. Também realiza campanhas de conscientização sobre adoção responsável e direitos dos animais, sensibilizando a comunidade para a importância do cuidado com os pets.', LOAD_FILE("images/forca-animal.png")),
('A ONG Um Lugar ao Sol apoia crianças e adolescentes em vulnerabilidade social, oferecendo atividades educativas e culturais. Seu objetivo é promover a inclusão, fortalecer a autoestima e ajudar os jovens a construir um futuro melhor.', LOAD_FILE("images/um-lugar-ao-sol.png")),
('A ONG Gerar foca na inclusão social de jovens e famílias em vulnerabilidade. Oferece educação, capacitação profissional e apoio psicossocial, visando empoderar os indivíduos para transformar suas vidas e alcançar autonomia. Além disso, promove atividades culturais e de lazer para o desenvolvimento comunitário.', LOAD_FILE("images/gerar.png")),
('A ONG TETO Brasil combate a pobreza e a desigualdade social em comunidades vulneráveis. Através da construção de moradias emergenciais e projetos de desenvolvimento comunitário, mobiliza voluntários para promover capacitação e empoderar famílias, buscando transformar realidades e garantir melhores condições de vida.', LOAD_FILE("images/teto.png"));

-- Inserindo os sites e redes sociais
INSERT INTO website (id_admin_ong, link_site, instagram, facebook) VALUES
((SELECT id_usuario FROM usuario WHERE nome = 'Pequeno Cotolengo'), 'http://pequenocotolengo.org', '@pequenocotolengo', 'facebook.com/pequenocotolengo'),
((SELECT id_usuario FROM usuario WHERE nome = 'Passos da Criança'), 'http://passosdacrianca.org', '@passosdacrianca', 'facebook.com/passosdacrianca'),
((SELECT id_usuario FROM usuario WHERE nome = 'Força Animal'), 'http://forcaanimal.org', '@forcaanimal', 'facebook.com/forcaanimal'),
((SELECT id_usuario FROM usuario WHERE nome = 'Um Lugar ao Sol'), 'http://umlugarauasol.org', '@umlugarauasol', 'facebook.com/umlugarauasol'),
((SELECT id_usuario FROM usuario WHERE nome = 'Gerar'), 'http://gerar.org', '@gerar', 'facebook.com/gerar'),
((SELECT id_usuario FROM usuario WHERE nome = 'TETO Brasil'), 'http://tetobrasil.org', '@tetobrasil', 'facebook.com/tetobrasil');