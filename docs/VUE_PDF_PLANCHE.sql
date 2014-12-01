DROP VIEW dbo.VUE_PDF_PLANCHE;

CREATE VIEW dbo.VUE_PDF_PLANCHE AS
  SELECT
      p.IDPlanche                                              AS IDPlanche
    , p.IDPlanchePrincipale                                    AS IDPlanchePrincipale
    , p.IDAtelier                                              AS IDAtelier
    , p.DateVisibleAtelier                                     AS DateVisibleAtelier
    , p.Quantité                                               AS NbFeuilles
    , dbo.IsZero(p.Largeur, pf.Largeur)                        AS Largeur
    , dbo.IsZero(p.Longueur, pf.Longueur)                      AS Longueur
    , p.DateExpeditionUrgente                                  AS ExpeSansFaconnage
    , p.DateExpeditionUrgenteFaconnage                         AS ExpeAvecFaconnage
    , dbo.f_dValeurOptionValeurPlanche(p.IDPlanche, 80, 1)     AS NbCouleursRecto
    , dbo.f_dValeurOptionValeurPlanche(p.IDPlanche, 81, 1)     AS NbCouleursVerso
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
    , act.LibelleTraduit                                       AS ActiviteProduction
    , p_soustraitance.IDPlanche                                AS IDPlancheSousTraitance
    , a_soustraitance.Nom                                      AS NomAtelierSousTraitance
    , a_principale.Nom                                         AS NomAtelierPlanchePrincipale
    , monteur.NomUtilisateur                                   AS NomMonteur
    , monteur.PrenomUtilisateur                                AS PrenomMonteur
    , monteur.EmailUtilisateur                                 AS EmailMonteur
    , p.DateAjout
    , p1.Nom                                                   AS NomAtelier
    , (SELECT
      SUM(Cout)
       FROM TBL_COMMANDE_PR
       WHERE IDPlanche = p.IDPlanche)                          AS CoutPlanche
  FROM TBL_PLANCHE p
    LEFT JOIN TBL_PLANCHE_FORMAT pf ON pf.IDPlancheFormat = p.IDProduitPlancheFormat
    LEFT JOIN TBL_PLANCHE p_soustraitance ON p_soustraitance.IDPlanchePrincipale = p.IDPlanche
    LEFT JOIN TBL_ATELIER a_soustraitance ON p_soustraitance.IDAtelier = a_soustraitance.IDAtelier
    LEFT JOIN TBL_PLANCHE p_principale ON p_principale.IDPlanche = p.IDPlanchePrincipale
    LEFT JOIN TBL_ATELIER a_principale ON p_principale.IDAtelier = a_principale.IDAtelier
    LEFT JOIN TBL_UTILISATEUR monteur ON monteur.IDUtilisateur = p.IDUtilisateurPlancheur
    INNER JOIN TBL_ATELIER p1 ON p1.IDAtelier = p.IDAtelier
    LEFT JOIN TBL_PRODUIT_ACTIVITE_PRODUCTION_TRAD act ON act.IDProduitActiviteProduction = p.IDProduitActiviteProduction AND act.IDLangue = 1
