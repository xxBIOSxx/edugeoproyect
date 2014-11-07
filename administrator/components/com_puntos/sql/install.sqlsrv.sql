IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[#__puntos_marker]') AND type in (N'U'))
BEGIN
CREATE TABLE [#__puntos_marker] (
  [id] [INT] NOT NULL IDENTITY(1,1) PRIMARY KEY,
  [asset_id] [bigint] NOT NULL DEFAULT '0',
  [catid] [bigint] NOT NULL DEFAULT '0',
  [name] [NVARCHAR](255) NOT NULL,
  [street] [NVARCHAR](255) NOT NULL,
  [plz] [NVARCHAR](10) NOT NULL,
  [town] [NVARCHAR](255) NOT NULL,
  [country] [NVARCHAR](255) NOT NULL,
  [gmlat] [DECIMAL](10,6) NOT NULL,
  [gmlng] [DECIMAL](10,6) NOT NULL,
  [description] [NVARCHAR](max) NOT NULL,
  [description_small] [NVARCHAR](max) NOT NULL,
  [picture] [NVARCHAR](255) NOT NULL,
  [picture_thumb] [NVARCHAR](255) NOT NULL,
  [created_by_alias] [NVARCHAR](255) NOT NULL,
  [created_by_ip] [INT] NOT NULL,
  [created_by] [INT] NOT NULL,
  [created] [DATETIME] NOT NULL,
  [vote] [FLOAT] NOT NULL DEFAULT '0',
  [votenum] [INT] NOT NULL,
  [published] [TINYINT] NOT NULL DEFAULT '0',
  [publish_up] [DATETIME] NOT NULL DEFAULT '0000-00-00 00:00:00',
  [publish_down] [DATETIME] NOT NULL DEFAULT '0000-00-00 00:00:00',
  [modified] [DATETIME] NOT NULL DEFAULT '0000-00-00 00:00:00',
  [modified_by] [INT] NOT NULL,
  [params] [TEXT] NOT NULL,
  [language] [CHAR](7) NOT NULL,
  [access] [INT] NOT NULL DEFAULT '0',
  [import_table] [NVARCHAR](255) NOT NULL,
  [import_id] [INT] NOT NULL
)
CREATE NonClustered Index gmlat ON [#__puntos_marker]
(gmlat ASC)
CREATE NonClustered Index gmlng ON [#__puntos_marker]
(gmlng ASC)
CREATE NonClustered Index catid ON [#__puntos_marker]
(catid ASC)
END;

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[#__puntos_categorie]') AND type in (N'U'))
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[#__puntos_categorie]') AND type in (N'U'))
BEGIN
CREATE TABLE  [#__puntos_categorie] (
  [id] [INT] NOT NULL IDENTITY(1,1) PRIMARY KEY,
  [cat_name] [NVARCHAR](255) NOT NULL,
  [cat_description] [NVARCHAR](max) NOT NULL,
  [cat_autor] [NVARCHAR](255) NOT NULL,
  [cat_icon] [NVARCHAR](255) NOT NULL,
  [cat_shadowicon] [NVARCHAR](255) NOT NULL,
  [cat_date] [DATETIME] NOT NULL,
  [published] tinyint NOT NULL DEFAULT '0',
  [count] [INT] NOT NULL,
  [cat_image] [NVARCHAR](255) NOT NULL,
  [import_table] [NVARCHAR](255) NOT NULL,
  [import_id] [INT] NOT NULL,
  [params] text NOT NULL
)
END;

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[#__puntos_kmls]') AND type in (N'U'))
BEGIN
CREATE TABLE [#__puntos_kmls] (
  [puntos_kml_id] [BIGINT] NOT NULL IDENTITY(1,1) PRIMARY KEY,
  [title] [NVARCHAR](255) NOT NULL,
  [catid] [INT] NOT NULL,
  [description] text NOT NULL,
  [original_filename] [NVARCHAR](1024) NOT NULL,
  [mangled_filename] [NVARCHAR](1024) NOT NULL,
  [mime_type] [NVARCHAR](255) NOT NULL DEFAULT 'application/octet-stream',
  [created] [DATETIME] NOT NULL DEFAULT '0000-00-00 00:00:00',
  [created_by] [BIGINT] NOT NULL DEFAULT '0',
  [state] tinyint NOT NULL DEFAULT '1'
)
END;

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[#__puntos_version]') AND type in (N'U'))
BEGIN
CREATE TABLE [#__puntos_version] (
  [id] [BIGINT] NOT NULL IDENTITY(1,1) PRIMARY KEY,
  [version] [NVARCHAR](1024) NOT NULL
)
END;
