Create Database `base_teste`;
Use base_teste;
-- ------------------------------------------------------------
-- Nome do suporte.
-- ------------------------------------------------------------

CREATE TABLE suport_album (
  id_suport_album INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  suport_album_nome CHAR(70) NOT NULL,
  PRIMARY KEY(id_suport_album)
);

CREATE TABLE faq (
  id_faq INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  faq_titulo VARCHAR(60) NOT NULL,
  faq_texto TEXT NOT NULL,
  PRIMARY KEY(id_faq)
);

-- ------------------------------------------------------------
-- Estatuto do registado, exemplos: Aluno, professor, administrador, empregada, etc. 
-- Para um estatuo estão previstos os seguintes previlegios por defeito:
-- ->O utilizador pode criar um tópico
-- ->O utilizador pode responder a um tópico
-- ------------------------------------------------------------

CREATE TABLE estatuto (
  id_estatuto INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  estatuto_nome VARCHAR(35) NOT NULL,
  estatuto_gerir_topi BOOL NOT NULL DEFAULT 0,
  estatuto_gerir_post BOOL NOT NULL DEFAULT 0,
  estatuto_gerir_area BOOL NOT NULL DEFAULT 0,
  estatuto_req_filme BOOL NOT NULL DEFAULT 0,
  estatuto_req_album BOOL NOT NULL DEFAULT 0,
  estatuto_req_outro BOOL NOT NULL DEFAULT 0,
  estatuto_gerir_filme BOOL NOT NULL DEFAULT 0,
  estatuto_gerir_album BOOL NOT NULL DEFAULT 0,
  estatuto_gerir_outro BOOL NOT NULL DEFAULT 0,
  estatuto_gerir_faq BOOL NOT NULL DEFAULT 0,
  estatuto_gerir_registo BOOL NOT NULL DEFAULT 0,
  estatuto_gerir_estatuto BOOL NOT NULL DEFAULT 0,
  estatuto_gerir_frases BOOL NOT NULL DEFAULT 0,
  PRIMARY KEY(id_estatuto)
);

-- ------------------------------------------------------------
-- Frase que deve ser apresentada ao utilizador.
-- ------------------------------------------------------------

CREATE TABLE frase (
  id_frase INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  frase_texto MEDIUMTEXT NOT NULL,
  PRIMARY KEY(id_frase)
);

-- ------------------------------------------------------------
-- Quando uma requesição é apagada o seu backup é feito automáticamente para esta tabela, que não terá nenhuma relação com as outras.
-- Porque se quer que os dados desta sejam mais precistentes, ou seja este log de requesições não pode ser apagado e/ou modificado.
-- ------------------------------------------------------------

CREATE TABLE requesicao_log (
  id_requesicao_log INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  requesicao_log_numero CHAR(20) NOT NULL,
  requesicao_log_item_nome VARCHAR(70) NOT NULL,
  requesicao_log_suporte_nome VARCHAR(70) NOT NULL,
  requesicao_log_levantado DATE NOT NULL,
  requesicao_log_requerido DATE NOT NULL,
  requesicao_log_multa_paga FLOAT NOT NULL DEFAULT 0,
  requesicao_log_apagado DATE NOT NULL,
  requesicao_log_tipo SMALLINT NOT NULL,
  PRIMARY KEY(id_requesicao_log)
);

CREATE TABLE geral (
  id_geral INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  id_elemento TINYINT UNSIGNED NOT NULL,
  PRIMARY KEY(id_geral)
);

-- ------------------------------------------------------------
-- Nome dos gêneros.
-- ------------------------------------------------------------

CREATE TABLE genero_filme (
  id_genero_filme INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  genero_filme_nome CHAR(70) NULL,
  PRIMARY KEY(id_genero_filme)
);

CREATE TABLE tipo_som_filme (
  id_tipo_som_filme INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  tipo_som_filme_nome VARCHAR(40) NULL,
  PRIMARY KEY(id_tipo_som_filme)
);

-- ------------------------------------------------------------
-- Área a que petencem X post  dentro de X posts.
-- ------------------------------------------------------------

CREATE TABLE area (
  id_area INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  area_nome TINYTEXT NOT NULL,
  area_descricao MEDIUMTEXT NOT NULL,
  PRIMARY KEY(id_area)
);

-- ------------------------------------------------------------
-- Relaçao entre gêneros e trilhas.
-- ------------------------------------------------------------

CREATE TABLE trilha_genero_album (
  id_trilha_genero_album INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  trilha_genero_album_album_nome VARCHAR(100) NULL,
  PRIMARY KEY(id_trilha_genero_album)
);

CREATE TABLE suport_filme (
  id_suport_filme INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  suport_filme_nome VARCHAR(50) NULL,
  PRIMARY KEY(id_suport_filme)
);

-- ------------------------------------------------------------
-- Direitos de autor.
-- ------------------------------------------------------------

CREATE TABLE direito_outro (
  id_direito_outro INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  direito_outro_outro_nome CHAR(70) NOT NULL,
  PRIMARY KEY(id_direito_outro)
);

CREATE TABLE suport_outro (
  id_suport_outro INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  suport_outro_nome VARCHAR(70) NOT NULL,
  PRIMARY KEY(id_suport_outro)
);

-- ------------------------------------------------------------
-- Dados relativos ao registado na página.
-- ------------------------------------------------------------

CREATE TABLE registo (
  id_registo INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  estatuto_id_estatuto INTEGER UNSIGNED NOT NULL,
  registo_nick VARCHAR(15) NOT NULL,
  registo_pass VARCHAR(100) NOT NULL,
  registo_data DATE NOT NULL,
  registo_data_nas DATE NOT NULL,
  registo_avatar MEDIUMTEXT NULL,
  registo_ass VARCHAR(150) NULL,
  registo_numero CHAR(20) NOT NULL,
  registo_data_ultima DATETIME NOT NULL,
  registo_homepage TINYTEXT NULL,
  registo_mail VARCHAR(35) NULL,
  registo_sha1 BOOL NOT NULL,
  registo_nome_pri VARCHAR(15) NOT NULL,
  registo_nome_ult VARCHAR(15) NOT NULL,
  registo_is_activo DATE NOT NULL DEFAULT 00000000,
  registo_online DATETIME NULL,
  registo_online MEDIUMTEXT NULL,
  PRIMARY KEY(id_registo, estatuto_id_estatuto),
  INDEX registo_FKIndex1(estatuto_id_estatuto),
  FOREIGN KEY(estatuto_id_estatuto)
    REFERENCES estatuto(id_estatuto)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
);

-- ------------------------------------------------------------
-- Dados relativos a cada tópico.
-- ------------------------------------------------------------

CREATE TABLE topico (
  id_topico INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  area_id_area INTEGER UNSIGNED NOT NULL,
  topico_visto INTEGER UNSIGNED NOT NULL DEFAULT 0,
  topico_pode_comentar BOOL NOT NULL DEFAULT true,
  topico_sticky BOOL NOT NULL DEFAULT false,
  PRIMARY KEY(id_topico, area_id_area),
  INDEX topico_FKIndex1(area_id_area),
  FOREIGN KEY(area_id_area)
    REFERENCES `area`(id_area)
      ON DELETE CASCADE
      ON UPDATE CASCADE
);

-- ------------------------------------------------------------
-- Mensagens internas entre utilizadores.
-- Do gênero caixa de correio.
-- ------------------------------------------------------------

CREATE TABLE mensagem (
  id_mensagem INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  registo_id_registo INTEGER UNSIGNED NOT NULL,
  registo_estatuto_id_estatuto INTEGER UNSIGNED NOT NULL,
  mensagem_destinatario INTEGER UNSIGNED NOT NULL,
  mensagem_data DATETIME NOT NULL,
  mensagem_corpo TEXT NOT NULL,
  mensagem_assunto VARCHAR(50) NOT NULL,
  PRIMARY KEY(id_mensagem, registo_id_registo, registo_estatuto_id_estatuto),
  INDEX mensagem_FKIndex1(registo_id_registo, registo_estatuto_id_estatuto),
  FOREIGN KEY(registo_id_registo, registo_estatuto_id_estatuto)
    REFERENCES registo(id_registo, estatuto_id_estatuto)
      ON DELETE CASCADE
      ON UPDATE CASCADE
);

-- ------------------------------------------------------------
-- A duração total é o somatório das durações das trilhas do álbum.
-- ------------------------------------------------------------

CREATE TABLE album (
  geral_id_geral INTEGER UNSIGNED NOT NULL,
  album_etiqueta VARCHAR(25) NOT NULL,
  album_nome CHAR(100) NOT NULL,
  album_sinopse MEDIUMTEXT NULL,
  album_ano YEAR NULL,
  album_requesitavel BOOL NOT NULL DEFAULT 1,
  album_classi INTEGER UNSIGNED NOT NULL DEFAULT 0,
  album_classi_num_vot INTEGER UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY(geral_id_geral),
  INDEX album_FKIndex1(geral_id_geral),
  FOREIGN KEY(geral_id_geral)
    REFERENCES geral(id_geral)
      ON DELETE CASCADE
      ON UPDATE CASCADE
);

-- ------------------------------------------------------------
-- Copias disponiveis num formato.
-- As cópias disponíveis quer para filmes, álbuns ou outros são sempre as cópias totais menos as requesições.
-- ------------------------------------------------------------

CREATE TABLE copi_album (
  album_geral_id_geral INTEGER UNSIGNED NOT NULL,
  suport_album_id_suport_album INTEGER UNSIGNED NOT NULL,
  copi_album_totais SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY(album_geral_id_geral, suport_album_id_suport_album),
  INDEX copi_album_FKIndex1(album_geral_id_geral),
  INDEX copi_album_FKIndex2(suport_album_id_suport_album),
  FOREIGN KEY(album_geral_id_geral)
    REFERENCES album(geral_id_geral)
      ON DELETE CASCADE
      ON UPDATE CASCADE,
  FOREIGN KEY(suport_album_id_suport_album)
    REFERENCES suport_album(id_suport_album)
      ON DELETE NO ACTION
      ON UPDATE CASCADE
);

-- ------------------------------------------------------------
-- Dados relativos a uma trilha do álbum.
-- ------------------------------------------------------------

CREATE TABLE trilha_album (
  id_trilha INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  album_geral_id_geral INTEGER UNSIGNED NOT NULL,
  trilha_genero_album_id_trilha_genero_album INTEGER UNSIGNED NOT NULL,
  trilha_album_nome CHAR(100) NOT NULL,
  trilha_album_duracao TIME NULL,
  trilha_album_acerca TEXT NULL,
  trilha_album_ordem INTEGER UNSIGNED NOT NULL,
  PRIMARY KEY(id_trilha, album_geral_id_geral, trilha_genero_album_id_trilha_genero_album),
  INDEX trilha_FKIndex1(album_geral_id_geral),
  INDEX trilha_FKIndex2(trilha_genero_album_id_trilha_genero_album),
  FOREIGN KEY(album_geral_id_geral)
    REFERENCES album(geral_id_geral)
      ON DELETE CASCADE
      ON UPDATE CASCADE,
  FOREIGN KEY(trilha_genero_album_id_trilha_genero_album)
    REFERENCES trilha_genero_album(id_trilha_genero_album)
      ON DELETE NO ACTION
      ON UPDATE CASCADE
);

CREATE TABLE controlo_votacao (
  registo_estatuto_id_estatuto INTEGER UNSIGNED NOT NULL,
  registo_id_registo INTEGER UNSIGNED NOT NULL,
  geral_id_geral INTEGER UNSIGNED NOT NULL,
  PRIMARY KEY(registo_estatuto_id_estatuto, registo_id_registo, geral_id_geral),
  INDEX controlo_votacao_FKIndex1(registo_id_registo, registo_estatuto_id_estatuto),
  INDEX controlo_votacao_FKIndex2(geral_id_geral),
  FOREIGN KEY(registo_id_registo, registo_estatuto_id_estatuto)
    REFERENCES registo(id_registo, estatuto_id_estatuto)
      ON DELETE CASCADE
      ON UPDATE CASCADE,
  FOREIGN KEY(geral_id_geral)
    REFERENCES geral(id_geral)
      ON DELETE CASCADE
      ON UPDATE CASCADE
);

-- ------------------------------------------------------------
-- Dados relativos ao post (comentários).
-- ------------------------------------------------------------

CREATE TABLE post (
  id_post INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  registo_id_registo INTEGER UNSIGNED NOT NULL,
  topico_area_id_area INTEGER UNSIGNED NOT NULL,
  topico_id_topico INTEGER UNSIGNED NOT NULL,
  registo_estatuto_id_estatuto INTEGER UNSIGNED NOT NULL,
  post_titulo VARCHAR(80) NOT NULL,
  post_data_hora DATETIME NOT NULL,
  post_activo BOOL NOT NULL DEFAULT true,
  post_texto TEXT NOT NULL,
  post_prin BOOL NOT NULL DEFAULT false,

  PRIMARY KEY(id_post, registo_id_registo, topico_area_id_area, topico_id_topico, registo_estatuto_id_estatuto),
  INDEX post_FKIndex1(registo_id_registo, registo_estatuto_id_estatuto),
  INDEX post_FKIndex2(topico_id_topico, topico_area_id_area),
  FOREIGN KEY(registo_id_registo, registo_estatuto_id_estatuto)
    REFERENCES registo(id_registo, estatuto_id_estatuto)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(topico_id_topico, topico_area_id_area)
    REFERENCES topico(id_topico, area_id_area)
      ON DELETE CASCADE
      ON UPDATE CASCADE
);

CREATE TABLE requesicao (
  id_requesicao INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  registo_estatuto_id_estatuto INTEGER UNSIGNED NOT NULL,
  registo_id_registo INTEGER UNSIGNED NOT NULL,
  geral_id_geral INTEGER UNSIGNED NOT NULL,
  requesicao_dat_min DATE NOT NULL,
  requesicao_dia_levantado DATE NOT NULL DEFAULT 00000000,
  requesicao_id_suporte INTEGER UNSIGNED NULL,
  PRIMARY KEY(id_requesicao, registo_estatuto_id_estatuto, registo_id_registo, geral_id_geral),
  INDEX requesicao_FKIndex1(registo_id_registo, registo_estatuto_id_estatuto),
  INDEX requesicao_FKIndex2(geral_id_geral),
  FOREIGN KEY(registo_id_registo, registo_estatuto_id_estatuto)
    REFERENCES registo(id_registo, estatuto_id_estatuto)
      ON DELETE CASCADE
      ON UPDATE CASCADE,
  FOREIGN KEY(geral_id_geral)
    REFERENCES geral(id_geral)
      ON DELETE CASCADE
      ON UPDATE CASCADE
);

-- ------------------------------------------------------------
-- Identificação de outro elemento que não seja filme ou álbum.
-- ------------------------------------------------------------

CREATE TABLE outro (
  geral_id_geral INTEGER UNSIGNED NOT NULL,
  direito_outro_outro_id_direito_outro INTEGER UNSIGNED NOT NULL,
  outro_nome CHAR(25) NOT NULL,
  outro_sinopse TEXT NULL,
  outro_ano YEAR NULL,
  outro_requesitavel BOOL NOT NULL DEFAULT 1,
  outro_classi INTEGER UNSIGNED NOT NULL DEFAULT 0,
  outro_classi_num_vot INTEGER UNSIGNED NOT NULL DEFAULT 0,
  outro_etiqueta VARCHAR(25) NOT NULL,
  PRIMARY KEY(geral_id_geral, direito_outro_outro_id_direito_outro),
  INDEX outro_FKIndex1(direito_outro_outro_id_direito_outro),
  INDEX outro_FKIndex2(geral_id_geral),
  FOREIGN KEY(direito_outro_outro_id_direito_outro)
    REFERENCES direito_outro(id_direito_outro)
      ON DELETE NO ACTION
      ON UPDATE CASCADE,
  FOREIGN KEY(geral_id_geral)
    REFERENCES geral(id_geral)
      ON DELETE CASCADE
      ON UPDATE CASCADE
);

-- ------------------------------------------------------------
-- Dados relativos aos filmes.
-- ------------------------------------------------------------

CREATE TABLE filme (
  geral_id_geral INTEGER UNSIGNED NOT NULL,
  genero_filme_filme_id_genero_filme INTEGER UNSIGNED NOT NULL,
  tipo_som_filme_id_tipo_som_filme INTEGER UNSIGNED NOT NULL,
  filme_etiqueta VARCHAR(10) NOT NULL,
  filme_nome VARCHAR(70) NOT NULL,
  filme_slogan VARCHAR(150) NULL,
  filme_sinopse MEDIUMTEXT NOT NULL,
  filme_ano YEAR NOT NULL,
  filme_duracao INTEGER UNSIGNED NOT NULL,
  filme_imdb FLOAT NULL,
  filme_requesitavel BOOL NOT NULL DEFAULT 1,
  filme_classi INTEGER UNSIGNED NOT NULL DEFAULT 0,
  filme_classi_num_vot INTEGER UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY(geral_id_geral, genero_filme_filme_id_genero_filme, tipo_som_filme_id_tipo_som_filme),
  INDEX filme_FKIndex1(tipo_som_filme_id_tipo_som_filme),
  INDEX filme_FKIndex2(genero_filme_filme_id_genero_filme),
  INDEX filme_FKIndex3(geral_id_geral),
  FOREIGN KEY(tipo_som_filme_id_tipo_som_filme)
    REFERENCES tipo_som_filme(id_tipo_som_filme)
      ON DELETE NO ACTION
      ON UPDATE CASCADE,
  FOREIGN KEY(genero_filme_filme_id_genero_filme)
    REFERENCES genero_filme(id_genero_filme)
      ON DELETE NO ACTION
      ON UPDATE CASCADE,
  FOREIGN KEY(geral_id_geral)
    REFERENCES geral(id_geral)
      ON DELETE CASCADE
      ON UPDATE CASCADE
);

CREATE TABLE realizador_filme (
  id_realizador INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  filme_genero_filme_filme_id_genero_filme INTEGER UNSIGNED NOT NULL,
  filme_geral_id_geral INTEGER UNSIGNED NOT NULL,
  filme_tipo_som_filme_id_tipo_som_filme INTEGER UNSIGNED NOT NULL,
  realizador_filme_nome VARCHAR(70) NOT NULL,
  PRIMARY KEY(id_realizador, filme_genero_filme_filme_id_genero_filme, filme_geral_id_geral, filme_tipo_som_filme_id_tipo_som_filme),
  INDEX realizador_FKIndex1(filme_genero_filme_filme_id_genero_filme, filme_geral_id_geral, filme_tipo_som_filme_id_tipo_som_filme),
  FOREIGN KEY(filme_genero_filme_filme_id_genero_filme, filme_geral_id_geral, filme_tipo_som_filme_id_tipo_som_filme)
    REFERENCES filme(genero_filme_filme_id_genero_filme, geral_id_geral, tipo_som_filme_id_tipo_som_filme)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
);

CREATE TABLE num_suport_album (
  copi_album_suport_album_id_suport_album INTEGER UNSIGNED NOT NULL,
  copi_album_album_geral_id_geral INTEGER UNSIGNED NOT NULL,
  char_num_suport_album CHAR NOT NULL,
  PRIMARY KEY(copi_album_suport_album_id_suport_album, copi_album_album_geral_id_geral),
  INDEX num_suport_album_FKIndex1(copi_album_album_geral_id_geral, copi_album_suport_album_id_suport_album),
  FOREIGN KEY(copi_album_album_geral_id_geral, copi_album_suport_album_id_suport_album)
    REFERENCES copi_album(album_geral_id_geral, suport_album_id_suport_album)
      ON DELETE CASCADE
      ON UPDATE CASCADE
);

-- ------------------------------------------------------------
-- Número de cópias disponíveis e totais naquele formato.
-- ------------------------------------------------------------

CREATE TABLE copi_filme (
  filme_genero_filme_filme_id_genero_filme INTEGER UNSIGNED NOT NULL,
  filme_geral_id_geral INTEGER UNSIGNED NOT NULL,
  filme_tipo_som_filme_id_tipo_som_filme INTEGER UNSIGNED NOT NULL,
  suport_filme_id_suport_filme INTEGER UNSIGNED NOT NULL,
  copi_filme_totais SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY(filme_genero_filme_filme_id_genero_filme, filme_geral_id_geral, filme_tipo_som_filme_id_tipo_som_filme, suport_filme_id_suport_filme),
  INDEX copi_filme_FKIndex1(filme_genero_filme_filme_id_genero_filme, filme_geral_id_geral, filme_tipo_som_filme_id_tipo_som_filme),
  INDEX copi_filme_FKIndex2(suport_filme_id_suport_filme),
  FOREIGN KEY(filme_genero_filme_filme_id_genero_filme, filme_geral_id_geral, filme_tipo_som_filme_id_tipo_som_filme)
    REFERENCES filme(genero_filme_filme_id_genero_filme, geral_id_geral, tipo_som_filme_id_tipo_som_filme)
      ON DELETE CASCADE
      ON UPDATE CASCADE,
  FOREIGN KEY(suport_filme_id_suport_filme)
    REFERENCES suport_filme(id_suport_filme)
      ON DELETE NO ACTION
      ON UPDATE CASCADE
);

-- ------------------------------------------------------------
-- Copias totais e disponiveis naquele suporte.
-- ------------------------------------------------------------

CREATE TABLE copi_outro (
  outro_geral_id_geral INTEGER UNSIGNED NOT NULL,
  outro_direito_outro_outro_id_direito_outro INTEGER UNSIGNED NOT NULL,
  suport_outro_id_suport_outro INTEGER UNSIGNED NOT NULL,
  copi_outro_totais SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY(outro_geral_id_geral, outro_direito_outro_outro_id_direito_outro, suport_outro_id_suport_outro),
  INDEX suporte_interactivo_FKIndex1(outro_geral_id_geral, outro_direito_outro_outro_id_direito_outro),
  INDEX copi_outro_FKIndex2(suport_outro_id_suport_outro),
  FOREIGN KEY(outro_geral_id_geral, outro_direito_outro_outro_id_direito_outro)
    REFERENCES outro(geral_id_geral, direito_outro_outro_id_direito_outro)
      ON DELETE CASCADE
      ON UPDATE CASCADE,
  FOREIGN KEY(suport_outro_id_suport_outro)
    REFERENCES suport_outro(id_suport_outro)
      ON DELETE NO ACTION
      ON UPDATE CASCADE
);

CREATE TABLE num_suport_outro (
  copi_outro_outro_geral_id_geral INTEGER UNSIGNED NOT NULL,
  copi_outro_outro_direito_outro_outro_id_direito_outro INTEGER UNSIGNED NOT NULL,
  copi_outro_suport_outro_id_suport_outro INTEGER UNSIGNED NOT NULL,
  char_num_suport_outro CHAR NOT NULL,
  PRIMARY KEY(copi_outro_outro_geral_id_geral, copi_outro_outro_direito_outro_outro_id_direito_outro, copi_outro_suport_outro_id_suport_outro),
  INDEX num_suport_outro_FKIndex1(copi_outro_outro_geral_id_geral, copi_outro_outro_direito_outro_outro_id_direito_outro, copi_outro_suport_outro_id_suport_outro),
  FOREIGN KEY(copi_outro_outro_geral_id_geral, copi_outro_outro_direito_outro_outro_id_direito_outro, copi_outro_suport_outro_id_suport_outro)
    REFERENCES copi_outro(outro_geral_id_geral, outro_direito_outro_outro_id_direito_outro, suport_outro_id_suport_outro)
      ON DELETE CASCADE
      ON UPDATE CASCADE
);

CREATE TABLE num_suport_filme (
  copi_filme_filme_geral_id_geral INTEGER UNSIGNED NOT NULL,
  copi_filme_filme_genero_filme_filme_id_genero_filme INTEGER UNSIGNED NOT NULL,
  copi_filme_filme_tipo_som_filme_id_tipo_som_filme INTEGER UNSIGNED NOT NULL,
  copi_filme_suport_filme_id_suport_filme INTEGER UNSIGNED NOT NULL,
  char_num_suport_filme CHAR NOT NULL,
  PRIMARY KEY(copi_filme_filme_geral_id_geral, copi_filme_filme_genero_filme_filme_id_genero_filme, copi_filme_filme_tipo_som_filme_id_tipo_som_filme, copi_filme_suport_filme_id_suport_filme),
  INDEX num_suport_filme_FKIndex1(copi_filme_filme_genero_filme_filme_id_genero_filme, copi_filme_filme_geral_id_geral, copi_filme_filme_tipo_som_filme_id_tipo_som_filme, copi_filme_suport_filme_id_suport_filme),
  FOREIGN KEY(copi_filme_filme_genero_filme_filme_id_genero_filme, copi_filme_filme_geral_id_geral, copi_filme_filme_tipo_som_filme_id_tipo_som_filme, copi_filme_suport_filme_id_suport_filme)
    REFERENCES copi_filme(filme_genero_filme_filme_id_genero_filme, filme_geral_id_geral, filme_tipo_som_filme_id_tipo_som_filme, suport_filme_id_suport_filme)
      ON DELETE CASCADE
      ON UPDATE CASCADE
);


CREATE  TABLE IF NOT EXISTS `controlo_respeito` (
  `registo_id_registo` INT NOT NULL ,
  `registo_estatuto_id_estatuto` INT NOT NULL ,
  `post_id_post` INT NOT NULL ,
  `post_topico_id_topico` INT NOT NULL ,
  `post_topico_area_id_area` INT NOT NULL ,
  `post_registo_id_registo` INT NOT NULL ,
  `post_registo_estatuto_id_estatuto` INT NOT NULL ,
  `controlo_respeito_tipo` BOOLEAN NOT NULL,
  PRIMARY KEY (`registo_id_registo`, `registo_estatuto_id_estatuto`, `post_id_post`, `post_topico_id_topico`, `post_topico_area_id_area`, `post_registo_id_registo`, `post_registo_estatuto_id_estatuto`) ,
  CONSTRAINT `fk_cont_respeito_registo`
    FOREIGN KEY (`registo_id_registo` , `registo_estatuto_id_estatuto` )
    REFERENCES `alpha_project_teste`.`registo` (`id_registo` , `estatuto_id_estatuto` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cont_respeito_post`
    FOREIGN KEY (`post_id_post` , `post_topico_id_topico` , `post_topico_area_id_area` , `post_registo_id_registo` , `post_registo_estatuto_id_estatuto` )
    REFERENCES `alpha_project_teste`.`post` (`id_post` , `topico_id_topico` , `topico_area_id_area` , `registo_id_registo` , `registo_estatuto_id_estatuto` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

CREATE INDEX `fk_cont_respeito_registo` ON `controlo_respeito` (`registo_id_registo` ASC, `registo_estatuto_id_estatuto` ASC) ;

CREATE INDEX `fk_cont_respeito_post` ON `controlo_respeito` (`post_id_post` ASC, `post_topico_id_topico` ASC, `post_topico_area_id_area` ASC, `post_registo_id_registo` ASC, `post_registo_estatuto_id_estatuto` ASC) ;

CREATE  TABLE IF NOT EXISTS `bloco` (
  `id_bloco` INT NOT NULL AUTO_INCREMENT ,
  `bloco_ordem` INT NOT NULL ,
  `bloco_codigo` LONGTEXT NULL ,
  `bloco_date_up` DATETIME NOT NULL ,
  `bloco_nome` MEDIUMTEXT NULL ,
  `bloco_active` BOOLEAN NOT NULL DEFAULT true ,
  PRIMARY KEY (`id_bloco`) );
  
  
CREATE  TABLE IF NOT EXISTS `session_control` (
  `id_session_control` VARCHAR(200) NOT NULL ,
  `registo_id_registo` INT NOT NULL ,
  `registo_estatuto_id_estatuto` INT NOT NULL ,
  PRIMARY KEY (`id_session_control`, `registo_id_registo`, `registo_estatuto_id_estatuto`) ,
  INDEX `fk_session_registo` (`registo_id_registo` ASC, `registo_estatuto_id_estatuto` ASC) ,
  CONSTRAINT `fk_session_registo`
    FOREIGN KEY (`registo_id_registo` , `registo_estatuto_id_estatuto` )
    REFERENCES `registo` (`id_registo` , `estatuto_id_estatuto` )
    ON DELETE CASCADE
    ON UPDATE CASCADE);