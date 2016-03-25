fSET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `alpha_project_teste` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `alpha_project_teste`;

-- -----------------------------------------------------
-- Table `alpha_project_teste`.`geral`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `alpha_project_teste`.`geral` (
  `id_geral` INT NOT NULL AUTO_INCREMENT ,
  `id_elemento` TINYINT NOT NULL ,
  PRIMARY KEY (`id_geral`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alpha_project_teste`.`genero_filme`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `alpha_project_teste`.`genero_filme` (
  `id_genero_filme` INT NOT NULL AUTO_INCREMENT ,
  `genero_filme_nome` VARCHAR(70) NOT NULL ,
  PRIMARY KEY (`id_genero_filme`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alpha_project_teste`.`tipo_som_filme`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `alpha_project_teste`.`tipo_som_filme` (
  `id_tipo_som_filme` INT NOT NULL AUTO_INCREMENT ,
  `tipo_som_filme_nome` VARCHAR(40) NOT NULL ,
  PRIMARY KEY (`id_tipo_som_filme`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alpha_project_teste`.`filme`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `alpha_project_teste`.`filme` (
  `filme_etiqueta` VARCHAR(25) NOT NULL ,
  `filme_nome` VARCHAR(70) NOT NULL ,
  `filme_slogan` VARCHAR(150) NULL ,
  `filme_sinopse` MEDIUMTEXT NOT NULL ,
  `filme_ano` YEAR NOT NULL ,
  `filme_duracao` INT NOT NULL ,
  `filme_imdb` VARCHAR(45) NULL ,
  `filme_requesitavel` BOOLEAN NOT NULL DEFAULT 1 ,
  `filme_classi` INT NOT NULL DEFAULT 0 ,
  `filme_classi_num_vot` INT NOT NULL DEFAULT 0 ,
  `geral_id_geral` INT NOT NULL ,
  `genero_filme_id_genero_filme` INT NOT NULL ,
  `tipo_som_filme_id_tipo_som_filme` INT NOT NULL ,
  PRIMARY KEY (`geral_id_geral`, `genero_filme_id_genero_filme`, `tipo_som_filme_id_tipo_som_filme`) ,
  INDEX `fk_filme_geral` (`geral_id_geral` ASC) ,
  INDEX `fk_filme_genero_filme` (`genero_filme_id_genero_filme` ASC) ,
  INDEX `fk_filme_tipo_som_filme` (`tipo_som_filme_id_tipo_som_filme` ASC) ,
  CONSTRAINT `fk_filme_geral`
    FOREIGN KEY (`geral_id_geral` )
    REFERENCES `alpha_project_teste`.`geral` (`id_geral` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_filme_genero_filme`
    FOREIGN KEY (`genero_filme_id_genero_filme` )
    REFERENCES `alpha_project_teste`.`genero_filme` (`id_genero_filme` )
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_filme_tipo_som_filme`
    FOREIGN KEY (`tipo_som_filme_id_tipo_som_filme` )
    REFERENCES `alpha_project_teste`.`tipo_som_filme` (`id_tipo_som_filme` )
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alpha_project_teste`.`realizador_filme`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `alpha_project_teste`.`realizador_filme` (
  `id_realizador` INT NOT NULL AUTO_INCREMENT ,
  `realizador_filme_nome` VARCHAR(100) NOT NULL ,
  `filme_geral_id_geral` INT NOT NULL ,
  `filme_genero_filme_id_genero_filme` INT NOT NULL ,
  `filme_tipo_som_filme_id_tipo_som_filme` INT NOT NULL ,
  PRIMARY KEY (`id_realizador`, `filme_geral_id_geral`, `filme_genero_filme_id_genero_filme`, `filme_tipo_som_filme_id_tipo_som_filme`) ,
  INDEX `fk_realizador_filme_filme` (`filme_geral_id_geral` ASC, `filme_genero_filme_id_genero_filme` ASC, `filme_tipo_som_filme_id_tipo_som_filme` ASC) ,
  CONSTRAINT `fk_realizador_filme_filme`
    FOREIGN KEY (`filme_geral_id_geral` , `filme_genero_filme_id_genero_filme` , `filme_tipo_som_filme_id_tipo_som_filme` )
    REFERENCES `alpha_project_teste`.`filme` (`geral_id_geral` , `genero_filme_id_genero_filme` , `tipo_som_filme_id_tipo_som_filme` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alpha_project_teste`.`suport_filme`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `alpha_project_teste`.`suport_filme` (
  `id_suport_filme` INT NOT NULL AUTO_INCREMENT ,
  `suport_filme_nome` VARCHAR(50) NOT NULL ,
  PRIMARY KEY (`id_suport_filme`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alpha_project_teste`.`copi_num_filme`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `alpha_project_teste`.`copi_num_filme` (
  `copi_filme_totais` INT NULL DEFAULT 0 ,
  `suport_filme_id_suport_filme` INT NOT NULL ,
  `filme_geral_id_geral` INT NOT NULL ,
  `filme_genero_filme_id_genero_filme` INT NOT NULL ,
  `filme_tipo_som_filme_id_tipo_som_filme` INT NOT NULL ,
  PRIMARY KEY (`suport_filme_id_suport_filme`, `filme_geral_id_geral`, `filme_genero_filme_id_genero_filme`, `filme_tipo_som_filme_id_tipo_som_filme`) ,
  INDEX `fk_copi_num_filme_suport_filme` (`suport_filme_id_suport_filme` ASC) ,
  INDEX `fk_copi_num_filme_filme` (`filme_geral_id_geral` ASC, `filme_genero_filme_id_genero_filme` ASC, `filme_tipo_som_filme_id_tipo_som_filme` ASC) ,
  CONSTRAINT `fk_copi_num_filme_suport_filme`
    FOREIGN KEY (`suport_filme_id_suport_filme` )
    REFERENCES `alpha_project_teste`.`suport_filme` (`id_suport_filme` )
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_copi_num_filme_filme`
    FOREIGN KEY (`filme_geral_id_geral` , `filme_genero_filme_id_genero_filme` , `filme_tipo_som_filme_id_tipo_som_filme` )
    REFERENCES `alpha_project_teste`.`filme` (`geral_id_geral` , `genero_filme_id_genero_filme` , `tipo_som_filme_id_tipo_som_filme` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alpha_project_teste`.`num_suport_filme`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `alpha_project_teste`.`num_suport_filme` (
  `char_num_suport_filme` CHAR NOT NULL ,
  `copi_num_filme_suport_filme_id_suport_filme` INT NOT NULL ,
  `copi_num_filme_filme_geral_id_geral` INT NOT NULL ,
  `copi_num_filme_filme_genero_filme_id_genero_filme` INT NOT NULL ,
  `copi_num_filme_filme_tipo_som_filme_id_tipo_som_filme` INT NOT NULL ,
  PRIMARY KEY (`copi_num_filme_suport_filme_id_suport_filme`, `copi_num_filme_filme_geral_id_geral`, `copi_num_filme_filme_genero_filme_id_genero_filme`, `copi_num_filme_filme_tipo_som_filme_id_tipo_som_filme`) ,
  INDEX `fk_num_suport_filme_copi_num_filme` (`copi_num_filme_suport_filme_id_suport_filme` ASC, `copi_num_filme_filme_geral_id_geral` ASC, `copi_num_filme_filme_genero_filme_id_genero_filme` ASC, `copi_num_filme_filme_tipo_som_filme_id_tipo_som_filme` ASC) ,
  CONSTRAINT `fk_num_suport_filme_copi_num_filme`
    FOREIGN KEY (`copi_num_filme_suport_filme_id_suport_filme` , `copi_num_filme_filme_geral_id_geral` , `copi_num_filme_filme_genero_filme_id_genero_filme` , `copi_num_filme_filme_tipo_som_filme_id_tipo_som_filme` )
    REFERENCES `alpha_project_teste`.`copi_num_filme` (`suport_filme_id_suport_filme` , `filme_geral_id_geral` , `filme_genero_filme_id_genero_filme` , `filme_tipo_som_filme_id_tipo_som_filme` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alpha_project_teste`.`estatuto`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `alpha_project_teste`.`estatuto` (
  `id_estatuto` INT NOT NULL AUTO_INCREMENT ,
  `estatuto_nome` VARCHAR(100) NOT NULL ,
  `estatuto_gerir_topi` BOOLEAN NOT NULL ,
  `estatuto_gerir_post` BOOLEAN NOT NULL ,
  `estatuto_gerir_area` BOOLEAN NOT NULL ,
  `estatuto_req_filme` BOOLEAN NOT NULL ,
  `estatuto_req_album` BOOLEAN NOT NULL ,
  `estatuto_req_outro` BOOLEAN NOT NULL ,
  `estatuto_gerir_filme` BOOLEAN NOT NULL ,
  `estatuto_gerir_album` BOOLEAN NOT NULL ,
  `estatuto_gerir_outro` BOOLEAN NOT NULL ,
  `estatuto_gerir_faq` BOOLEAN NOT NULL ,
  `estatuto_gerir_registo` BOOLEAN NOT NULL ,
  `estatuto_gerir_estatuto` BOOLEAN NOT NULL ,
  `estatuto_gerir_frases` BOOLEAN NOT NULL ,
  PRIMARY KEY (`id_estatuto`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alpha_project_teste`.`registo`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `alpha_project_teste`.`registo` (
  `id_registo` INT NOT NULL AUTO_INCREMENT ,
  `registo_nick` VARCHAR(15) NOT NULL ,
  `registo_pass` VARCHAR(100) NOT NULL ,
  `registo_data` DATE NOT NULL ,
  `registo_data_nas` DATE NOT NULL ,
  `registo_avatar` MEDIUMTEXT NULL ,
  `registo_ass` VARCHAR(150) NULL ,
  `registo_numero` VARCHAR(20) NOT NULL ,
  `registo_data_ultima` DATETIME NOT NULL ,
  `registo_homepage` TINYTEXT NULL ,
  `registo_mail` VARCHAR(45) NULL ,
  `registo_sha1` BOOLEAN NOT NULL ,
  `registo_nome_pri` VARCHAR(20) NOT NULL ,
  `registo_nome_ult` VARCHAR(20) NOT NULL ,
  `registo_is_activo` DATE NOT NULL ,
  `registo_online` DATETIME NULL ,
  `registo_online` MEDIUMTEXT NULL,
  `estatuto_id_estatuto` INT NOT NULL ,
  PRIMARY KEY (`id_registo`, `estatuto_id_estatuto`) ,
  INDEX `fk_registo_estatuto` (`estatuto_id_estatuto` ASC) ,
  CONSTRAINT `fk_registo_estatuto`
    FOREIGN KEY (`estatuto_id_estatuto` )
    REFERENCES `alpha_project_teste`.`estatuto` (`id_estatuto` )
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alpha_project_teste`.`controlo_votacao`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `alpha_project_teste`.`controlo_votacao` (
  `registo_id_registo` INT NOT NULL ,
  `registo_estatuto_id_estatuto` INT NOT NULL ,
  `geral_id_geral` INT NOT NULL ,
  PRIMARY KEY (`registo_id_registo`, `registo_estatuto_id_estatuto`, `geral_id_geral`) ,
  INDEX `fk_controlo_votacao_registo` (`registo_id_registo` ASC, `registo_estatuto_id_estatuto` ASC) ,
  INDEX `fk_controlo_votacao_geral` (`geral_id_geral` ASC) ,
  CONSTRAINT `fk_controlo_votacao_registo`
    FOREIGN KEY (`registo_id_registo` , `registo_estatuto_id_estatuto` )
    REFERENCES `alpha_project_teste`.`registo` (`id_registo` , `estatuto_id_estatuto` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_controlo_votacao_geral`
    FOREIGN KEY (`geral_id_geral` )
    REFERENCES `alpha_project_teste`.`geral` (`id_geral` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alpha_project_teste`.`requesicao`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `alpha_project_teste`.`requesicao` (
  `id_requesicao` INT NOT NULL AUTO_INCREMENT ,
  `requesicao_dat_min` DATE NOT NULL ,
  `requesicao_dia_levantado` DATE NOT NULL ,
  `requesicao_id_suporte` INT NULL ,
  `registo_id_registo` INT NOT NULL ,
  `registo_estatuto_id_estatuto` INT NOT NULL ,
  `geral_id_geral` INT NOT NULL ,
  PRIMARY KEY (`id_requesicao`, `registo_id_registo`, `registo_estatuto_id_estatuto`, `geral_id_geral`) ,
  INDEX `fk_requesicao_registo` (`registo_id_registo` ASC, `registo_estatuto_id_estatuto` ASC) ,
  INDEX `fk_requesicao_geral` (`geral_id_geral` ASC) ,
  CONSTRAINT `fk_requesicao_registo`
    FOREIGN KEY (`registo_id_registo` , `registo_estatuto_id_estatuto` )
    REFERENCES `alpha_project_teste`.`registo` (`id_registo` , `estatuto_id_estatuto` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_requesicao_geral`
    FOREIGN KEY (`geral_id_geral` )
    REFERENCES `alpha_project_teste`.`geral` (`id_geral` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alpha_project_teste`.`frase`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `alpha_project_teste`.`frase` (
  `id_frase` INT NOT NULL AUTO_INCREMENT ,
  `frase_texto` MEDIUMTEXT NOT NULL ,
  PRIMARY KEY (`id_frase`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alpha_project_teste`.`faq`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `alpha_project_teste`.`faq` (
  `id_faq` INT NOT NULL AUTO_INCREMENT ,
  `faq_titulo` MEDIUMTEXT NOT NULL ,
  `faq_texto` TEXT NOT NULL ,
  PRIMARY KEY (`id_faq`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alpha_project_teste`.`area`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `alpha_project_teste`.`area` (
  `id_area` INT NOT NULL AUTO_INCREMENT ,
  `area_nome` TINYTEXT NOT NULL ,
  `area_descricao` MEDIUMTEXT NULL ,
  PRIMARY KEY (`id_area`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alpha_project_teste`.`topico`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `alpha_project_teste`.`topico` (
  `id_topico` INT NOT NULL AUTO_INCREMENT ,
  `topico_visto` INT NOT NULL DEFAULT 0 ,
  `topico_pode_comentar` BOOLEAN NOT NULL DEFAULT TRUE ,
  `area_id_area` INT NOT NULL ,
  `topico_sticky` BOOL NOT NULL DEFAULT false,
  PRIMARY KEY (`id_topico`, `area_id_area`) ,
  INDEX `fk_topico_area` (`area_id_area` ASC) ,
  CONSTRAINT `fk_topico_area`
    FOREIGN KEY (`area_id_area` )
    REFERENCES `alpha_project_teste`.`area` (`id_area` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alpha_project_teste`.`post`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `alpha_project_teste`.`post` (
  `id_post` INT NOT NULL AUTO_INCREMENT ,
  `post_titulo` VARCHAR(80) NOT NULL ,
  `post_data_hora` DATETIME NOT NULL ,
  `post_activo` BOOLEAN NOT NULL DEFAULT TRUE ,
  `post_texto` TEXT NOT NULL ,
  `post_prin` BOOLEAN NOT NULL DEFAULT FALSE ,
  `topico_id_topico` INT NOT NULL ,
  `topico_area_id_area` INT NOT NULL ,
  `registo_id_registo` INT NOT NULL ,
  `registo_estatuto_id_estatuto` INT NOT NULL ,
  PRIMARY KEY (`id_post`, `topico_id_topico`, `topico_area_id_area`, `registo_id_registo`, `registo_estatuto_id_estatuto`) ,
  INDEX `fk_post_topico` (`topico_id_topico` ASC, `topico_area_id_area` ASC) ,
  INDEX `fk_post_registo` (`registo_id_registo` ASC, `registo_estatuto_id_estatuto` ASC) ,
  CONSTRAINT `fk_post_topico`
    FOREIGN KEY (`topico_id_topico` , `topico_area_id_area` )
    REFERENCES `alpha_project_teste`.`topico` (`id_topico` , `area_id_area` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_post_registo`
    FOREIGN KEY (`registo_id_registo` , `registo_estatuto_id_estatuto` )
    REFERENCES `alpha_project_teste`.`registo` (`id_registo` , `estatuto_id_estatuto` )
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alpha_project_teste`.`album`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `alpha_project_teste`.`album` (
  `geral_id_geral` INT NOT NULL ,
  `album_etiqueta` VARCHAR(25) NOT NULL ,
  `album_nome` VARCHAR(100) NOT NULL ,
  `album_sinopse` MEDIUMTEXT NULL ,
  `album_ano` YEAR NOT NULL ,
  `album_requesitavel` BOOLEAN NOT NULL DEFAULT TRUE ,
  `album_classi` INT NOT NULL DEFAULT 0 ,
  `album_classi_num_vot` INT NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`geral_id_geral`) ,
  INDEX `fk_album_geral` (`geral_id_geral` ASC) ,
  CONSTRAINT `fk_album_geral`
    FOREIGN KEY (`geral_id_geral` )
    REFERENCES `alpha_project_teste`.`geral` (`id_geral` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alpha_project_teste`.`trilha_genero_album`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `alpha_project_teste`.`trilha_genero_album` (
  `id_trilha_genero_album` INT NOT NULL AUTO_INCREMENT ,
  `trilha_genero_album_album_nome` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`id_trilha_genero_album`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alpha_project_teste`.`trilha_album`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `alpha_project_teste`.`trilha_album` (
  `idtrilha_album` INT NOT NULL ,
  `trilha_album_nome` VARCHAR(100) NOT NULL ,
  `trilha_album_duracao` TIME NOT NULL ,
  `trilha_album_acerca` MEDIUMTEXT NULL ,
  `trilha_album_ordem` INT NOT NULL DEFAULT 0 ,
  `trilha_genero_album_id_trilha_genero_album` INT NOT NULL ,
  `album_geral_id_geral` INT NOT NULL ,
  PRIMARY KEY (`idtrilha_album`, `trilha_genero_album_id_trilha_genero_album`, `album_geral_id_geral`) ,
  INDEX `fk_trilha_album_trilha_genero_album` (`trilha_genero_album_id_trilha_genero_album` ASC) ,
  INDEX `fk_trilha_album_album` (`album_geral_id_geral` ASC) ,
  CONSTRAINT `fk_trilha_album_trilha_genero_album`
    FOREIGN KEY (`trilha_genero_album_id_trilha_genero_album` )
    REFERENCES `alpha_project_teste`.`trilha_genero_album` (`id_trilha_genero_album` )
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_trilha_album_album`
    FOREIGN KEY (`album_geral_id_geral` )
    REFERENCES `alpha_project_teste`.`album` (`geral_id_geral` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alpha_project_teste`.`suport_album`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `alpha_project_teste`.`suport_album` (
  `id_suport_album` INT NOT NULL AUTO_INCREMENT ,
  `suport_album_nome` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`id_suport_album`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alpha_project_teste`.`copi_album`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `alpha_project_teste`.`copi_album` (
  `album_geral_id_geral` INT NOT NULL ,
  `suport_album_id_suport_album` INT NOT NULL ,
  `copi_album_totais` SMALLINT NULL DEFAULT 0 ,
  PRIMARY KEY (`album_geral_id_geral`, `suport_album_id_suport_album`) ,
  INDEX `fk_copi_album_album` (`album_geral_id_geral` ASC) ,
  INDEX `fk_copi_album_suport_album` (`suport_album_id_suport_album` ASC) ,
  CONSTRAINT `fk_copi_album_album`
    FOREIGN KEY (`album_geral_id_geral` )
    REFERENCES `alpha_project_teste`.`album` (`geral_id_geral` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_copi_album_suport_album`
    FOREIGN KEY (`suport_album_id_suport_album` )
    REFERENCES `alpha_project_teste`.`suport_album` (`id_suport_album` )
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alpha_project_teste`.`num_suport_album`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `alpha_project_teste`.`num_suport_album` (
  `char_num_suport_album` CHAR NOT NULL DEFAULT 'A' ,
  `copi_album_album_geral_id_geral` INT NOT NULL ,
  `copi_album_suport_album_id_suport_album` INT NOT NULL ,
  PRIMARY KEY (`copi_album_album_geral_id_geral`, `copi_album_suport_album_id_suport_album`) ,
  INDEX `fk_num_suport_album_copi_album` (`copi_album_album_geral_id_geral` ASC, `copi_album_suport_album_id_suport_album` ASC) ,
  CONSTRAINT `fk_num_suport_album_copi_album`
    FOREIGN KEY (`copi_album_album_geral_id_geral` , `copi_album_suport_album_id_suport_album` )
    REFERENCES `alpha_project_teste`.`copi_album` (`album_geral_id_geral` , `suport_album_id_suport_album` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alpha_project_teste`.`direito_outro`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `alpha_project_teste`.`direito_outro` (
  `id_direito_outro` INT NOT NULL AUTO_INCREMENT ,
  `direito_outro_outro_nome` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`id_direito_outro`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alpha_project_teste`.`outro`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `alpha_project_teste`.`outro` (
  `geral_id_geral` INT NOT NULL AUTO_INCREMENT ,
  `outro_nome` VARCHAR(100) NOT NULL ,
  `outro_sinopse` TEXT NULL ,
  `outro_ano` YEAR NOT NULL ,
  `outro_requesitavel` BOOLEAN NOT NULL ,
  `outro_classi` INT NOT NULL DEFAULT 0 ,
  `outro_classi_num_vot` INT NOT NULL DEFAULT 0 ,
  `outro_etiqueta` VARCHAR(25) NOT NULL ,
  `direito_outro_id_direito_outro` INT NOT NULL ,
  PRIMARY KEY (`geral_id_geral`, `direito_outro_id_direito_outro`) ,
  INDEX `fk_outro_geral` (`geral_id_geral` ASC) ,
  INDEX `fk_outro_direito_outro` (`direito_outro_id_direito_outro` ASC) ,
  CONSTRAINT `fk_outro_geral`
    FOREIGN KEY (`geral_id_geral` )
    REFERENCES `alpha_project_teste`.`geral` (`id_geral` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_outro_direito_outro`
    FOREIGN KEY (`direito_outro_id_direito_outro` )
    REFERENCES `alpha_project_teste`.`direito_outro` (`id_direito_outro` )
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alpha_project_teste`.`suport_outro`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `alpha_project_teste`.`suport_outro` (
  `id_suport_outro` INT NOT NULL AUTO_INCREMENT ,
  `suport_outro_nome` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`id_suport_outro`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alpha_project_teste`.`copi_outro`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `alpha_project_teste`.`copi_outro` (
  `copi_outro_totais` INT NULL ,
  `suport_outro_id_suport_outro` INT NOT NULL ,
  `outro_geral_id_geral` INT NOT NULL ,
  `outro_direito_outro_id_direito_outro` INT NOT NULL ,
  PRIMARY KEY (`suport_outro_id_suport_outro`, `outro_geral_id_geral`, `outro_direito_outro_id_direito_outro`) ,
  INDEX `fk_copi_outro_suport_outro` (`suport_outro_id_suport_outro` ASC, `outro_direito_outro_id_direito_outro` ASC) ,
  INDEX `fk_copi_outro_outro` (`outro_geral_id_geral` ASC, `outro_direito_outro_id_direito_outro` ASC) ,
  CONSTRAINT `fk_copi_outro_suport_outro`
    FOREIGN KEY (`suport_outro_id_suport_outro` , `outro_direito_outro_id_direito_outro` )
    REFERENCES `alpha_project_teste`.`suport_outro` (`id_suport_outro` )
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_copi_outro_outro`
    FOREIGN KEY (`outro_geral_id_geral` , `outro_direito_outro_id_direito_outro` )
    REFERENCES `alpha_project_teste`.`outro` (`geral_id_geral` , `direito_outro_id_direito_outro` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alpha_project_teste`.`num_suport_outro`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `alpha_project_teste`.`num_suport_outro` (
  `copi_outro_suport_outro_id_suport_outro` INT NOT NULL ,
  `copi_outro_outro_geral_id_geral` INT NOT NULL ,
  `copi_outro_outro_direito_outro_id_direito_outro` INT NOT NULL ,
  `char_num_suport_outro` CHAR NOT NULL DEFAULT 'A' ,
  PRIMARY KEY (`copi_outro_suport_outro_id_suport_outro`, `copi_outro_outro_geral_id_geral`, `copi_outro_outro_direito_outro_id_direito_outro`) ,
  INDEX `fk_num_suport_outro_copi_outro` (`copi_outro_suport_outro_id_suport_outro` ASC, `copi_outro_outro_geral_id_geral` ASC, `copi_outro_outro_direito_outro_id_direito_outro` ASC) ,
  CONSTRAINT `fk_num_suport_outro_copi_outro`
    FOREIGN KEY (`copi_outro_suport_outro_id_suport_outro` , `copi_outro_outro_geral_id_geral` , `copi_outro_outro_direito_outro_id_direito_outro` )
    REFERENCES `alpha_project_teste`.`copi_outro` (`suport_outro_id_suport_outro` , `outro_geral_id_geral` , `outro_direito_outro_id_direito_outro` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alpha_project_teste`.`requesicao_log`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `alpha_project_teste`.`requesicao_log` (
  `id_requesicao_log` INT NOT NULL AUTO_INCREMENT ,
  `requesicao_log_numero` VARCHAR(20) NOT NULL ,
  `requesicao_log_item_nome` VARCHAR(100) NOT NULL ,
  `requesicao_log_suport_nome` VARCHAR(100) NOT NULL ,
  `requesicao_log_levantado` DATE NOT NULL ,
  `requesicao_log_multa_paga` FLOAT NOT NULL ,
  `requesicao_log_apagado` DATE NOT NULL ,
  `requesicao_log_requerido` DATE NOT NULL ,
  `requesicao_log_tipo` SMALLINT NOT NULL ,
  PRIMARY KEY (`id_requesicao_log`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alpha_project_teste`.`mensagem`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `alpha_project_teste`.`mensagem` (
  `id_mensagem` INT NOT NULL AUTO_INCREMENT ,
  `mensagem_destinatario` INT NOT NULL ,
  `mensagem_data` DATETIME NOT NULL ,
  `mensagem_corpo` TEXT NOT NULL ,
  `mensagem_assunto` VARCHAR(100) NOT NULL ,
  `registo_id_registo` INT NOT NULL ,
  `registo_estatuto_id_estatuto` INT NOT NULL ,
  PRIMARY KEY (`id_mensagem`, `registo_id_registo`, `registo_estatuto_id_estatuto`) ,
  INDEX `fk_mensagem_registo` (`registo_id_registo` ASC, `registo_estatuto_id_estatuto` ASC) ,
  CONSTRAINT `fk_mensagem_registo`
    FOREIGN KEY (`registo_id_registo` , `registo_estatuto_id_estatuto` )
    REFERENCES `alpha_project_teste`.`registo` (`id_registo` , `estatuto_id_estatuto` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `alpha_project_teste`.`controlo_respeito`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `alpha_project_teste`.`controlo_respeito` (
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
ENGINE = InnoDB;

CREATE INDEX `fk_cont_respeito_registo` ON `alpha_project_teste`.`controlo_respeito` (`registo_id_registo` ASC, `registo_estatuto_id_estatuto` ASC) ;

CREATE INDEX `fk_cont_respeito_post` ON `alpha_project_teste`.`controlo_respeito` (`post_id_post` ASC, `post_topico_id_topico` ASC, `post_topico_area_id_area` ASC, `post_registo_id_registo` ASC, `post_registo_estatuto_id_estatuto` ASC) ;

CREATE  TABLE IF NOT EXISTS `alpha_project_teste`.`bloco` (
  `id_bloco` INT NOT NULL AUTO_INCREMENT ,
  `bloco_ordem` INT NOT NULL ,
  `bloco_codigo` LONGTEXT NULL ,
  `bloco_date_up` DATETIME NOT NULL ,
  `bloco_nome` MEDIUMTEXT NULL ,
  `bloco_active` BOOLEAN NOT NULL DEFAULT true ,
  PRIMARY KEY (`id_bloco`) );
ENGINE = InnoDB;

CREATE  TABLE IF NOT EXISTS `alpha_project_teste`.`session_control` (
  `id_session_control` VARCHAR(200) NOT NULL ,
  `registo_id_registo` INT NOT NULL ,
  `registo_estatuto_id_estatuto` INT NOT NULL ,
  PRIMARY KEY (`id_session_control`, `registo_id_registo`, `registo_estatuto_id_estatuto`) ,
  INDEX `fk_session_registo` (`registo_id_registo` ASC, `registo_estatuto_id_estatuto` ASC) ,
  CONSTRAINT `fk_session_registo`
    FOREIGN KEY (`registo_id_registo` , `registo_estatuto_id_estatuto` )
    REFERENCES `alpha_project_teste`.`registo` (`id_registo` , `estatuto_id_estatuto` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;
	
SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
