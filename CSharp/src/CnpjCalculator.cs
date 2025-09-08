using System.Text.RegularExpressions;

namespace CnpjValidator;

public static class CnpjCalculator
{
    private static readonly Regex OnlyBaseAlfa = new Regex(@"^[0-9A-Z]{12}$", RegexOptions.Compiled);
    private static readonly Regex OnlyBaseNum = new Regex(@"^[0-9]{12}$", RegexOptions.Compiled);

    /// <summary>
    /// Método único para validar CNPJ (clássico ou alfanumérico).
    /// </summary>
    public static bool ValidarCNPJ(string cnpj)
    {
        if (string.IsNullOrWhiteSpace(cnpj)) return false;

        string limpo = LimparMascara(cnpj).ToUpperInvariant();

        if (limpo.Length != 14) return false;

        string base12 = limpo.Substring(0, 12);
        string dvStr = limpo.Substring(12, 2);

        // se base12 é só dígitos => clássico
        if (OnlyBaseNum.IsMatch(base12))
        {
            return CalcularDvNumerico(base12, dvStr);
        }
        // se base12 contém letras => alfanumérico
        else if (OnlyBaseAlfa.IsMatch(base12))
        {
            return CalcularDvAlfanumerico(base12, dvStr);
        }

        return false;
    }

    /// <summary>
    /// Valida os dígitos verificadores de um CNPJ alfanumérico.
    /// </summary>
    private static bool CalcularDvAlfanumerico(string base12, string dvInformado)
    {
        if (string.IsNullOrWhiteSpace(base12) || string.IsNullOrWhiteSpace(dvInformado)) return false;
        base12 = LimparMascara(base12).ToUpperInvariant();
        dvInformado = dvInformado.Trim();

        if (!OnlyBaseAlfa.IsMatch(base12) || !Regex.IsMatch(dvInformado, @"^\d{2}$")) return false;

        int d1 = CalcularDv(base12, false);
        int d2 = CalcularDv(base12 + d1, true);
        return dvInformado == $"{d1}{d2}";
    }

    /// <summary>
    /// Valida os dígitos verificadores de um CNPJ tradicional (numérico).
    /// </summary>
    private static bool CalcularDvNumerico(string base12Numerica, string dvInformado)
    {
        if (string.IsNullOrWhiteSpace(base12Numerica) || string.IsNullOrWhiteSpace(dvInformado)) return false;
        base12Numerica = LimparMascara(base12Numerica);
        dvInformado = dvInformado.Trim();

        if (!OnlyBaseNum.IsMatch(base12Numerica) || !Regex.IsMatch(dvInformado, @"^\d{2}$")) return false;

        int d1 = CalcularDv(base12Numerica, false);
        int d2 = CalcularDv(base12Numerica + d1, true);
        return dvInformado == $"{d1}{d2}";
    }

    private static int CalcularDv(string texto, bool tamanhoComDv)
    {
        int len = tamanhoComDv ? 13 : 12;
        if (texto.Length != len)
            throw new ArgumentException($"Entrada deve ter {len} caracteres.", nameof(texto));

        int[] pesos = GerarPesos(len);

        int soma = 0;
        for (int i = 0; i < len; i++)
        {
            char c = texto[i];
            int valor = ValorParaCalculo(c);
            soma += valor * pesos[i];
        }

        int resto = soma % 11;
        return (resto == 0 || resto == 1) ? 0 : 11 - resto;
    }

    private static int ValorParaCalculo(char c)
    {
        if (char.IsDigit(c)) return c - '0';
        char u = char.ToUpperInvariant(c);
        if (u >= 'A' && u <= 'Z') return u - 48;
        throw new ArgumentException($"Caractere inválido: '{c}'. Use apenas [A-Z0-9].");
    }

    private static int[] GerarPesos(int length)
    {
        var pesos = new int[length];
        int w = 2;
        for (int i = length - 1; i >= 0; i--)
        {
            pesos[i] = w;
            w++;
            if (w > 9) w = 2;
        }
        return pesos;
    }

    private static string LimparMascara(string s)
        => new string((s ?? "").Where(ch => ch != '.' && ch != '/' && ch != '-' && !char.IsWhiteSpace(ch)).ToArray());
}
