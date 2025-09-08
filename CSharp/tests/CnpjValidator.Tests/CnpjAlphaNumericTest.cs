using CnpjValidator;
namespace CnpjValidator.Tests
{
    public class CnpjAlphaNumericTest
    {
        [Theory]
        [InlineData("JL.W1L.MRJ/YPJY-60")]
        [InlineData("YX.90G.R83/CRK8-31")]
        [InlineData("TY.AUX.PFZ/RHQR-14")]
        [InlineData("9M.JET.S7S/IXD5-83")]
        public void Deve_Validar_Cnpj_Alfanumerico_Correto(string cnpj)
        {
            Assert.True(CnpjCalculator.ValidarCNPJ(cnpj));
        }

        [Theory]
        [InlineData("JL.W1L.MRJ/YPJY-30")]
        [InlineData("YX.90G.R83/CRK8-51")]
        [InlineData("TY.AUX.PFZ/RHQR-74")]
        [InlineData("9M.JET.S7S/IXD5-93")]
        public void Deve_Invalidar_Cnpj_Alfanumerico_Com_Dv_Errado(string cnpj)
        {
            Assert.False(CnpjCalculator.ValidarCNPJ(cnpj));
        }

        [Theory]
        [InlineData("JT.W1L.MRJ/YPJY-60")]
        [InlineData("YV.90G.R83/CRK8-31")]
        [InlineData("TT.AUX.PFZ/RHQR-14")]
        [InlineData("9Z.JET.S7S/IXD5-83")]
        public void Deve_Invalidar_Cnpj_Alfanumerico_Com_Base_Invalida(string cnpj)
        {
            Assert.False(CnpjCalculator.ValidarCNPJ(cnpj));
        }
    }
}