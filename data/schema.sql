-- phpMyAdmin SQL Dump
-- version 3.3.2
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Mer 03 Novembre 2010 à 11:45
-- Version du serveur: 5.1.41
-- Version de PHP: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `octaveoctave`
--

-- --------------------------------------------------------

--
-- Structure de la table `minify_file`
--

CREATE TABLE IF NOT EXISTS `minify_file` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `minify_ticket`
--

CREATE TABLE IF NOT EXISTS `minify_ticket` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(50) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `minify_ticket_file`
--

CREATE TABLE IF NOT EXISTS `minify_ticket_file` (
  `ticket_id` int(11) unsigned NOT NULL,
  `file_id` int(11) unsigned NOT NULL,
  `order` int(11) unsigned NOT NULL,
  PRIMARY KEY (`ticket_id`,`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
