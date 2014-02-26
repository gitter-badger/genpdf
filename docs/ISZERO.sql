DROP FUNCTION ISZERO;

CREATE FUNCTION ISZERO(
  @Number       FLOAT,
  @IsZeroNumber FLOAT
)
  RETURNS FLOAT
AS
  BEGIN

    IF (@Number = 0)
      BEGIN
        SET @Number = @IsZeroNumber
      END

    RETURN (@Number)

  END