class CnpjCalculator {
    static validarCNPJ(cnpj) {
        const limpo = cnpj.toUpperCase().replace(/[^A-Z0-9]/g, '');
        if (limpo.length !== 14) return false;

        const base12 = limpo.substring(0, 12);
        const dv = limpo.substring(12, 14);

        if (/^[0-9]{12}$/.test(base12)) {
            return this.calcularDvNumerico(base12, dv);
        } else if (/^[0-9A-Z]{12}$/.test(base12)) {
            return this.calcularDvAlfanumerico(base12, dv);
        }
        return false;
    }

    static calcularDvNumerico(base12, dvInformado) {
        if (!/^[0-9]{12}$/.test(base12) || !/^[0-9]{2}$/.test(dvInformado)) return false;

        const d1 = this.calcularUmDv(base12, false);
        const d2 = this.calcularUmDv(base12 + d1, true);
        return dvInformado === d1.toString() + d2.toString();
    }

    static calcularDvAlfanumerico(base12, dvInformado) {
        if (!/^[0-9A-Z]{12}$/.test(base12) || !/^[0-9]{2}$/.test(dvInformado)) return false;

        const d1 = this.calcularUmDv(base12, false);
        const d2 = this.calcularUmDv(base12 + d1, true);
        return dvInformado === d1.toString() + d2.toString();
    }

    static calcularUmDv(texto, comDv) {
        const len = comDv ? 13 : 12;
        const pesos = this.gerarPesos(len);
        let soma = 0;

        for (let i = 0; i < len; i++) {
            soma += this.valorChar(texto[i]) * pesos[i];
        }

        const resto = soma % 11;
        return (resto === 0 || resto === 1) ? 0 : 11 - resto;
    }

    static valorChar(c) {
        const o = c.charCodeAt(0);
        if ((o >= 48 && o <= 57) || (o >= 65 && o <= 90)) {
            return o - 48;
        }
        throw new Error(`Caractere inválido: ${c}`);
    }

    static gerarPesos(len) {
        const pesos = [];
        let w = 2;

        for (let i = len - 1; i >= 0; i--) {
            pesos[i] = w;
            w++;
            if (w > 9) w = 2;
        }

        return pesos.sort((a, b) => a - b);
    }
}