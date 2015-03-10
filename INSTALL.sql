CREATE DATABASE IF NOT EXISTS oretter;
USE oretter;
CREATE TABLE IF NOT EXISTS `oretter_auth_tokens` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `auth_token` varchar(512) NOT NULL,
  `oauth_token` varchar(512) NOT NULL,
  `oauth_token_secret` varchar(512) NOT NULL,
  `modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;
