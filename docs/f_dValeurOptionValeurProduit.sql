CREATE FUNCTION dbo.f_dValeurOptionValeurPlanche(@IDPlanche INT, @IDProduitOption INT)
  RETURNS DECIMAL
AS
  BEGIN
    DECLARE @return DECIMAL;
    SELECT
      @return = CASE
        WHEN pov.EstSans = 1 THEN 0
        ELSE ISNULL(pov.ValeurNumParticularite, 4)
      END
    FROM TBL_PLANCHE_TL_PRODUIT_OPTION_VALEUR p_pov
      JOIN TBL_PRODUIT_OPTION_VALEUR pov ON p_pov.IDProduitOptionValeur = pov.IDProduitOptionValeur
    WHERE IDProduitOption = @IDProduitOption AND IDPlanche = @IDPlanche
    RETURN @return;
  END;