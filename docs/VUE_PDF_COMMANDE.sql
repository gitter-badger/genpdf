DROP VIEW dbo.VUE_PDF_COMMANDE;
CREATE VIEW dbo.VUE_PDF_COMMANDE AS
SELECT
  c.IDCommande
  , pc.IDPlanche
  , pc.EstColise
  , cl.DateExpedition
  , cl.DateImperatif
  , c.ReferenceClient
  , c.CommentairePAO
  , c.CheminRepVisuelControle
  , c.ImpressionEnlEtat
  , c.EstModeleCouleur
  , c.IDCommandePrincipale
  , c.IDClientAdresseLivraison
  , c.IDClient
  , c.IDTransporteur
  , t.CodeTransporteur
  , cl.Quantite
  , cl.Justificatif
  , cl.BAT
  , cl.BATNumerique
  , cl.CodeServiceTNT
  , cl.EstImperatif
  , cl.IdTypeImperatif
  , cl.NbPorteCarte
  , client.IDSociete
  , f.CommentaireAtelier                                     AS CommentaireAtelier
  , p.Code                                                   AS CodeProduit
  , ISNULL(v.CodePostal, a.CodePostalLivraison)              AS CodePostal
  , ISNULL(vp.CodePays, ap.CodePays)                         AS CodePays
  , dbo.f_nIDProduitOptionValeurProduit(p.IDProduit, 80, 1)  AS NbCouleursRecto
  , dbo.f_nIDProduitOptionValeurProduit(p.IDProduit, 81, 1)  AS NbCouleursVerso
  , dbo.f_mValeurOptionCommande(c.IDCommande, 83)            AS LargeurOuvert
  , dbo.f_mValeurOptionCommande(c.IDCommande, 84)            AS LongueurOuvert
  , dbo.f_nIDProduitOptionValeurProduit(p.IDProduit, 89, 1)  AS Decoupe
  , dbo.f_nIDProduitOptionValeurProduit(p.IDProduit, 90, 1)  AS Perforation
  , dbo.f_nIDProduitOptionValeurProduit(p.IDProduit, 98, 1)  AS Rainage
  , dbo.f_nIDProduitOptionValeurProduit(p.IDProduit, 99, 1)  AS Predecoupe
  , dbo.f_mValeurOptionCommande(c.IDCommande, 102)           AS LargeurFerme
  , dbo.f_mValeurOptionCommande(c.IDCommande, 103)           AS LongueurFerme
  , dbo.f_nIDProduitOptionValeurProduit(p.IDProduit, 104, 1) AS Pliage
  , dbo.f_nIDProduitOptionValeurProduit(p.IDProduit, 105, 1) AS DecoupeALaForme
  , sel.HasFormeDecoupeNumerique                             AS DecoupeALaFormeNumerique
  , pov_feuillet.ValeurNumParticularite                      AS NombreDeFeuillets
  , dbo.f_nIDProduitOptionValeurProduit(p.IDProduit, 147, 1) AS Encollage
  , cert.Nom                                                 AS Certification
  , atelier.Nom                                              AS NomAtelier
  , fac.HasDecoupeNumeriqueHG
  , fac.HasDecoupeNumeriqueHD
  , fac.HasDecoupeNumeriqueBD
  , fac.HasDecoupeNumeriqueBG
  , dbo.f_bCommandeExarush(c.IDCommande)                    as EstRush
FROM
  TBL_COMMANDE c
  JOIN TBL_COMMANDE_LIGNE cl ON cl.IDCommande = c.IDCommande
  JOIN TBL_PRODUIT p ON p.IDProduit = cl.IDProduit
  JOIN TBL_PLANCHE_TL_COMMANDE pc ON pc.IDCommande = c.IDCommande
  JOIN TBL_CLIENT_ADRESSELIVRAISON a ON a.IDClientAdresseLivraison = c.IDClientAdresseLivraison
  LEFT JOIN TBL_VILLE v ON v.IDVille = a.IDVille
  LEFT JOIN TBL_PAYS vp ON vp.IDPays = v.IDPaysVille
  LEFT JOIN TBL_PAYS ap ON ap.IDPays = a.IDPays
  JOIN TBL_CLIENT client ON client.IDClient = c.IDClient
  LEFT JOIN TBL_TRANSPORTEUR t ON t.IDTransporteur = c.IDTransporteur
  LEFT JOIN TBL_COMMANDE_TL_CERTIFICATION_SOCIETE AS comm_cert_societe ON comm_cert_societe.IDCommande = c.IDCommande
  LEFT JOIN TBL_CERTIFICATION_TL_SOCIETE AS cert_societe ON cert_societe.IDCertificationSociete = comm_cert_societe.IDCertificationSociete
  LEFT JOIN TBL_CERTIFICATION AS cert ON cert.IDCertification = cert_societe.IDCertification
  LEFT JOIN TBL_PLANCHE_TL_COMMANDE pc2 ON pc2.IDCommande = c.IDCommande AND pc2.EstColise = 1
  LEFT JOIN TBL_PLANCHE planche ON planche.IDPlanche = pc2.IDPlanche
  LEFT JOIN TBL_ATELIER atelier ON atelier.IDAtelier = planche.IDAtelier
  LEFT JOIN Sc_Front.EXP_BDC bdc ON bdc.IDCommande = cl.IDCommande
  LEFT JOIN Sc_Front.EXP_BDC_SELECTION_PRODUIT sel ON sel.IDSelectionProduit = bdc.IDSelectionProduit
  LEFT JOIN Sc_Front.EXP_BDC_FACONNAGE fac ON fac.IDFaconnage = sel.IDFaconnage
  LEFT JOIN TBL_PRODUIT_OPTION_VALEUR pov_feuillet ON pov_feuillet.IDProduitOptionValeur =dbo.f_nIDProduitOptionValeurProduit(p.IDProduit, 146, 1)
  OUTER APPLY(
    SELECT TOP 1 TBL_FQUALITE.CommentaireAtelier FROM TBL_FQUALITE WHERE IDCommande = c.IDCommandePrincipale ORDER BY IDFQualite DESC
  ) f