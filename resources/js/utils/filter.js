/**
 * Filtro aplicado a strings para exibição limitada em telas
 *
 * @param {String} value - String que será validada e truncada
 * @param {Integer} length - Quantia de caracateres máximo que poderão ser exibidos
 * @returns {String}
 */
export function truncate(value, length = 20) {
    if (!value) return '';
    return value.length <= length ? value : value.substring(0, length) + '..';
}
