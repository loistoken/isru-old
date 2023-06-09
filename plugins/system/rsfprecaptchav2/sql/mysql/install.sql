INSERT IGNORE INTO `#__rsform_config` (`SettingName`, `SettingValue`) VALUES ('recaptchav2.site.key', '');
INSERT IGNORE INTO `#__rsform_config` (`SettingName`, `SettingValue`) VALUES ('recaptchav2.secret.key', '');
INSERT IGNORE INTO `#__rsform_config` (`SettingName`, `SettingValue`) VALUES ('recaptchav2.language', 'auto');
INSERT IGNORE INTO `#__rsform_config` (`SettingName`, `SettingValue`) VALUES ('recaptchav2.noscript', '1');
INSERT IGNORE INTO `#__rsform_config` (`SettingName`, `SettingValue`) VALUES ('recaptchav2.asyncdefer', '0');

INSERT IGNORE INTO `#__rsform_component_types` (`ComponentTypeId`, `ComponentTypeName`) VALUES (2424, 'recaptchav2');

DELETE FROM `#__rsform_component_type_fields` WHERE ComponentTypeId = 2424;

INSERT IGNORE INTO `#__rsform_component_type_fields` (`ComponentTypeId`, `FieldName`, `FieldType`, `FieldValues`, `Properties`, `Ordering`) VALUES
(2424, 'NAME', 'textbox', '', '', 0),
(2424, 'CAPTION', 'textbox', '', '', 1),
(2424, 'ADDITIONALATTRIBUTES', 'textarea', '', '', 2),
(2424, 'DESCRIPTION', 'textarea', '', '', 3),
(2424, 'VALIDATIONMESSAGE', 'textarea', 'INVALIDINPUT', '', 4),
(2424, 'THEME', 'select', 'LIGHT\r\nDARK', '', 5),
(2424, 'TYPE', 'select', 'IMAGE\r\nAUDIO', '', 6),
(2424, 'SIZE', 'select', 'NORMAL\r\nCOMPACT\r\nINVISIBLE', '{"case":{"INVISIBLE":{"show":["BADGE"],"hide":[]},"NORMAL":{"show":[],"hide":["BADGE"]},"COMPACT":{"show":[],"hide":["BADGE"]}}}', 7),
(2424, 'BADGE', 'select', 'INLINE\r\nBOTTOMRIGHT\r\nBOTTOMLEFT', '', 8),
(2424, 'COMPONENTTYPE', 'hidden', '2424', '', 8);