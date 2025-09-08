import java.util.regex.*;

public class CnpjCalculator {
    private static final Pattern NUM_BASE = Pattern.compile("^[0-9]{12}$");
    private static final Pattern ALFA_BASE = Pattern.compile("^[0-9A-Z]{12}$");

    public static boolean validarCNPJ(String cnpj) {
        String limpo = cnpj.replaceAll("[^A-Z0-9]", "").toUpperCase();
        if (limpo.length() != 14) return false;

        String base12 = limpo.substring(0, 12);
        String dv = limpo.substring(12, 14);

        if (NUM_BASE.matcher(base12).matches()) {
            return calcularDvNumerico(base12, dv);
        } else if (ALFA_BASE.matcher(base12).matches()) {
            return calcularDvAlfanumerico(base12, dv);
        }
        return false;
    }

    private static boolean calcularDvNumerico(String base12, String dvInformado) {
        if (!NUM_BASE.matcher(base12).matches() || !dvInformado.matches("\\d{2}")) return false;
        int d1 = calcularUmDv(base12, false);
        int d2 = calcularUmDv(base12 + d1, true);
        return dvInformado.equals("" + d1 + d2);
    }

    private static boolean calcularDvAlfanumerico(String base12, String dvInformado) {
        if (!ALFA_BASE.matcher(base12).matches() || !dvInformado.matches("\\d{2}")) return false;
        int d1 = calcularUmDv(base12, false);
        int d2 = calcularUmDv(base12 + d1, true);
        return dvInformado.equals("" + d1 + d2);
    }

    private static int calcularUmDv(String texto, boolean comDv) {
        int len = comDv ? 13 : 12;
        int[] pesos = gerarPesos(len);
        int soma = 0;
        for (int i = 0; i < len; i++) {
            soma += valorChar(texto.charAt(i)) * pesos[i];
        }
        int resto = soma % 11;
        return (resto == 0 || resto == 1) ? 0 : 11 - resto;
    }

    private static int valorChar(char c) {
        int o = (int)c;
        if ((o >= 48 && o <= 57) || (o >= 65 && o <= 90)) {
            return o - 48;
        }
        throw new IllegalArgumentException("Caractere inválido: " + c);
    }

    private static int[] gerarPesos(int length) {
        int[] pesos = new int[length];
        int w = 2;
        for (int i = length - 1; i >= 0; i--) {
            pesos[i] = w;
            w++;
            if (w > 9) w = 2;
        }
        return pesos;
    }    
}
