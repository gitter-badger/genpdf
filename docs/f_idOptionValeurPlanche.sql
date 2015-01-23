CREATE FUNCTION dbo.f_idOptionValeurPlanche(@IDPlanche INT, @IDProduitOption INT)
  RETURNS INT
AS
  BEGIN
    DECLARE @return INT;
    SELECT
      @return = pov.IDProduitOptionValeur
    FROM TBL_PLANCHE_TL_PRODUIT_OPTION_VALEUR p_pov
      JOIN TBL_PRODUIT_OPTION_VALEUR pov ON p_pov.IDProduitOptionValeur = pov.IDProduitOptionValeur
    WHERE IDProduitOption = @IDProduitOption AND IDPlanche = @IDPlanche
    RETURN @return;
  END;