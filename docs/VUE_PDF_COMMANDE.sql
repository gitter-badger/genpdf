DROP VIEW dbo.VUE_PDF_COMMANDE;
CREATE VIEW dbo.VUE_PDF_COMMANDE AS
SELECT
  c.IDCommande
  , pc.IDPlanche
  , cl.DateExpedition
  , cl.DateImperatif
  , c.ReferenceClient
  , c.CommentairePAO
  , c.CheminRepVisuelControle
  , c.ImpressionEnlEtat
  , c.EstModeleCouleur
  , c.IDCommandePrincipale
  , cl.Quantite
  , cl.Justificatif
  , cl.BAT
  , cl.BATNumerique
  , cl.CodeServiceTNT
  , cl.EstImperatif
  , cl.IdTypeImperatif
  , client.IDSociete
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
  , dbo.f_nIDProduitOptionValeurProduit(p.IDProduit, 147, 1) AS Encollage
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