CREATE FUNCTION dbo.f_dValeurOptionValeurPlanche(@IDPlanche INT, @IDProduitOption INT, @RetourneNulSiValeurSans INT)
  RETURNS DECIMAL
AS
  BEGIN
    DECLARE @return DECIMAL;
    SELECT
      @return = pov.ValeurNumParticularite
    FROM TBL_PLANCHE_TL_PRODUIT_OPTION_VALEUR p_pov
      JOIN TBL_PRODUIT_OPTION_VALEUR pov ON p_pov.IDProduitOptionValeur = pov.IDProduitOptionValeur
    WHERE IDProduitOption = @IDProduitOption AND IDPlanche = @IDPlanche AND (
      ISNULL(pov.EstSans, 0) = 0 OR @RetourneNulSiValeurSans = 0
    )
    RETURN @return;
  END;