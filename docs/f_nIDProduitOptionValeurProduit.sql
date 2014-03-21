CREATE FUNCTION dbo.f_nIDProduitOptionValeurProduit(@IDProduit INT, @IDProduitOption INT, @RetourneNulSiValeurSans INT)
  RETURNS INT
AS
  BEGIN
    DECLARE @return INT;
    BEGIN
    SELECT
      @return = pov.IDProduitOptionValeur
    FROM
      TBL_PRODUIT_TL_OPTION_PRODUIT tlOp
      JOIN
      TBL_PRODUIT_TL_PRODUIT_OPTION_VALEUR_PRODUIT_OPTION_FAMILLE_PRODUIT tlPovPoFp
        ON
          tlPovPoFp.IDProduitTLProduitOptionValeurProduitOptionFamilleProduit =
          tlOp.IDProduitTLProduitOptionValeurProduitOptionFamilleProduit
      JOIN
      TBL_PRODUIT_OPTION_VALEUR pov
        ON
          tlPovPoFp.IDProduitOptionValeur = pov.IDProduitOptionValeur
    WHERE
      ISNULL(pov.EstSupp, 0) = 0
      AND tlPovPoFp.Actif = 1
      AND tlOp.IDProduit = @IDProduit
      AND pov.IDProduitOption = @IDProduitOption
      AND (
        @RetourneNulSiValeurSans = 0 OR ISNULL(EstSans, 0) = 0
      );
      END;
    RETURN @return;
  END;
