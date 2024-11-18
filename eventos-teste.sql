USE projeto_ongedin;

INSERT INTO evento (titulo, descricao, local_rua, local_numero, local_complemento, local_bairro, local_cidade, local_estado, local_pais)
VALUES ('Bazar da Amizade', 'Um evento para arrecadar fundos e promover a solidariedade entre a comunidade.', 'Rua da Amizade', '123', 'Próximo ao parque', 'Centro', 'Curitiba', 'PR', 'Brasil');

INSERT INTO admin_ong_cadastra_evento (id_admin_ong, id_evento, data_evento)
VALUES (1, 1, '2024-12-15');

INSERT INTO evento (titulo, descricao, local_rua, local_numero, local_complemento, local_bairro, local_cidade, local_estado, local_pais)
VALUES ('Caminhada Solidária "Passos para o Futuro"', 'Participe da Caminhada Solidária "Passos para o Futuro" das 9h00 às 12h00, e ajude a transformar a vida de crianças em situação de vulnerabilidade. Este evento visa arrecadar fundos para os projetos da ONG "Passos da Criança", promovendo educação, saúde e bem-estar para as crianças da nossa comunidade.', 'Rua ABC', '243', '', 'Batel', 'Curitiba', 'PR', 'Brasil');

INSERT INTO admin_ong_cadastra_evento (id_admin_ong, id_evento, data_evento)
VALUES (2, 2, '2024-12-01');

INSERT INTO evento (titulo, descricao, local_rua, local_numero, local_complemento, local_bairro, local_cidade, local_estado, local_pais)
VALUES ('Feira de Adoção', 'Participe da Feira de Adoção das 10h00 às 16h00, e ajude a dar uma nova chance a animais em situação de abandono. O evento tem como objetivo promover a adoção responsável e conscientizar a comunidade sobre a importância do cuidado e respeito aos animais.', 'Rua Azevedo', '201', 'Em frente ao parque', 'Juvevê', 'Curitiba', 'PR', 'Brasil');

INSERT INTO admin_ong_cadastra_evento (id_admin_ong, id_evento, data_evento)
VALUES (3, 3, '2024-12-20');

INSERT INTO evento (titulo, descricao, local_rua, local_numero, local_complemento, local_bairro, local_cidade, local_estado, local_pais)
VALUES ('Festival de Verão "Luz para o Futuro"', 'Participe do Festival de Verão "Luz para o Futuro" das 14h00 às 20h00, e ajude a promover o desenvolvimento e a inclusão social de crianças e jovens em situação de vulnerabilidade. O evento tem como objetivo arrecadar fundos para os projetos da ONG "Um Lugar ao Sol", que oferece apoio educacional, cultural e social.', 'Rua São Lourenço', '43', '', 'Água Verde', 'Curitiba', 'PR', 'Brasil');

INSERT INTO admin_ong_cadastra_evento (id_admin_ong, id_evento, data_evento)
VALUES (4, 4, '2025-01-03');

INSERT INTO evento (titulo, descricao, local_rua, local_numero, local_complemento, local_bairro, local_cidade, local_estado, local_pais)
VALUES ('Jantar Solidário', 'Participe do Jantar Solidário "Construindo Esperanças" das 19h00 às 22h00, e ajude a transformar a realidade de famílias que vivem em situação de vulnerabilidade. Este evento tem como objetivo arrecadar fundos para os projetos da ONG Teto Brasil, que visa a construção de moradias e a promoção de direitos.', 'Rua da Esperança', '459', 'Perto do Shopping', 'Xaxim', 'Curitiba', 'PR', 'Brasil');

INSERT INTO admin_ong_cadastra_evento (id_admin_ong, id_evento, data_evento)
VALUES (5, 5, '2025-01-12');

INSERT INTO evento (titulo, descricao, local_rua, local_numero, local_complemento, local_bairro, local_cidade, local_estado, local_pais)
VALUES ('Construção de Moradias em Comunidades Vulneráveis', 'Participe do evento da TETO Brasil para a construção de moradias emergenciais em comunidades vulneráveis. Durante o evento, voluntários e profissionais se unem para levar dignidade e infraestrutura básica para famílias em situação de risco. A sua participação vai ajudar a transformar a vida dessas famílias.', 'Rua das Flores', '120', '', 'Fazendinha', 'Curitiba', 'PR', 'Brasil');

INSERT INTO admin_ong_cadastra_evento (id_admin_ong, id_evento, data_evento)
VALUES (6, 6, '2024-12-10');