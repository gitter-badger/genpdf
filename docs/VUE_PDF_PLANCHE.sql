DROP VIEW dbo.VUE_PDF_PLANCHE;

CREATE VIEW dbo.VUE_PDF_PLANCHE AS
  SELECT
      p.IDPlanche                                              AS IDPlanche
    , p.IDPlanchePrincipale                                    AS IDPlanchePrincipale
    , p.IDAtelier                                              AS IDAtelier
    , p.DateVisibleAtelier                                     AS DateVisibleAtelier
    , Quantité                                                 AS NbFeuilles
    , dbo.IsZero(p.Largeur, pf.Largeur)                        AS Largeur
    , dbo.IsZero(p.Longueur, pf.Longueur)                      AS Longueur
    , DateExpeditionUrgente                                    AS ExpeSansFaconnage
    , DateExpeditionUrgenteFaconnage                           AS ExpeAvecFaconnage
    , dbo.f_nIDProduitOptionValeurPlanche(p.IDPlanche, 75, 1)  AS Support
    , dbo.f_nIDProduitOptionValeurPlanche(p.IDPlanche, 78, 1)  AS PelliculageRecto
    , dbo.f_nIDProduitOptionValeurPlanche(p.IDPlanche, 79, 1)  AS PelliculageVerso
    , dbo.f_nIDProduitOptionValeurPlanche(p.IDPlanche, 80, 1)  AS ImpressionRecto
    , dbo.f_nIDProduitOptionValeurPlanche(p.IDPlanche, 81, 1)  AS ImpressionVerso
    , dbo.f_nIDProduitOptionValeurPlanche(p.IDPlanche, 85, 1)  AS VernisRecto
    , dbo.f_nIDProduitOptionValeurPlanche(p.IDPlanche, 86, 1)  AS VernisVerso
    , dbo.f_nIDProduitOptionValeurPlanche(p.IDPlanche, 87, 1)  AS VernisSelectifRecto
    , dbo.f_nIDProduitOptionValeurPlanche(p.IDPlanche, 88, 1)  AS VernisSelectifVerso
    , dbo.f_nIDProduitOptionValeurPlanche(p.IDPlanche, 89, 1)  AS Decoupe
    , dbo.f_nIDProduitOptionValeurPlanche(p.IDPlanche, 90, 1)  AS Perforation
    , dbo.f_nIDProduitOptionValeurPlanche(p.IDPlanche, 93, 1)  AS DorureRecto
    , dbo.f_nIDProduitOptionValeurPlanche(p.IDPlanche, 96, 1)  AS TypeImpression
    , dbo.f_nIDProduitOptionValeurPlanche(p.IDPlanche, 98, 1)  AS Rainage
    , dbo.f_nIDProduitOptionValeurPlanche(p.IDPlanche, 99, 1)  AS Predecoupe
    , dbo.f_nIDProduitOptionValeurPlanche(p.IDPlanche, 100, 1) AS EncreAGratter
    , dbo.f_nIDProduitOptionValeurPlanche(p.IDPlanche, 104, 1) AS Pliage
    , dbo.f_nIDProduitOptionValeurPlanche(p.IDPlanche, 105, 1) AS DecoupeALaForme
    , dbo.f_nIDProduitOptionValeurPlanche(p.IDPlanche, 147, 1) AS Encollage
    , p.AvecDecoupe
    , p.AvecDorure
    , p.AvecEncollage
    , p.AvecEncre
    , p.AvecPerforation
    , p.AvecRainage
    , p.AvecPliage
    , p.Bascule
    , p.NbCoinsRonds
    , p.ObservationsTechnique
    , p.IDProduitPlancheFormat
    , p.EstAR
    , p.EstSousTraitance
    , p.IDProduitActiviteProduction
  FROM TBL_PLANCHE p
    LEFT JOIN TBL_PLANCHE_FORMAT pf ON pf.IDPlancheFormat = p.IDProduitPlancheFormat

