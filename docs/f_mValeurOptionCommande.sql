CREATE FUNCTION dbo.f_mValeurOptionCommande(@IDCommande INT, @IDProduitOption INT)
  RETURNS MONEY
AS
  BEGIN
    DECLARE @return MONEY;
    SELECT
      @return = Valeur
    FROM TBL_COMMANDE_LIGNE_TL_OPTION_PRODUIT clop
      JOIN TBL_COMMANDE_LIGNE cl ON cl.IDCommandeLigne = clop.IDCommandeLigne
      JOIN TBL_PRODUIT_TL_OPTION_PRODUIT pop ON pop.IDProduitTLOptionProduit = clop.IDProduitTLOptionProduit
      JOIN TBL_PRODUIT_TL_PRODUIT_OPTION_FAMILLE_PRODUIT pofp
        ON pofp.IDProduitTLProduitOptionFamilleProduit = pop.IDProduitTLProduitOptionFamilleProduit
    WHERE cl.IDCommande = @IDCommande AND pofp.IDProduitOption = @IDProduitOption;
    RETURN @return
  END
