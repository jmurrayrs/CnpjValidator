using CnpjValidator;

namespace CnpjValidator.Tests
{
    public class CnpjCalculatorTests
    {
        [Theory]
        [InlineData("55.493.873/0001-75")]
        [InlineData("85.678.544/0001-60")]
        [InlineData("24.047.184/0001-03")]
        public void Deve_Validar_Cnpj_Tradicional_Correto(string cnpj)
        {
            Assert.True(CnpjCalculator.ValidarCNPJ(cnpj));
        }

        [Theory]
        [InlineData("55.493.873/0001-72")]
        [InlineData("85.678.544/0001-65")]
        [InlineData("24.047.184/0001-20")]
        public void Deve_Invalidar_Cnpj_Tradicional_Com_Dv_Errado(string cnpj)
        {
            Assert.False(CnpjCalculator.ValidarCNPJ(cnpj));
        }


        [Theory]
        [InlineData("55.693.873/0001-75")]
        [InlineData("85.878.544/0001-60")]
        [InlineData("24.247.184/0001-03")]

        public void Deve_Invalidar_Cnpj_Tradicional_Com_Base_Invalida(string cnpj)
        {
            Assert.False(CnpjCalculator.ValidarCNPJ(cnpj));
        }

    }

}