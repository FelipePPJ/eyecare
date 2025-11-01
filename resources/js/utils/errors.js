/**
 * Leitura e tratativa de erros retornados do back. Dependendo da validação,
 * o back pode enviar um objeto com mais de uma camada Ex.: errors.exams.0.exam_id
 * Esta função fará o flatten das mensagens para uma única
 *
 * @param {Object} errors
 * @returns {Object}
 */
export function flattenErrors(errors) {
    const flattened = {};

    for (const key in errors) {
        const shortKey = key.split('.').pop();
        flattened[shortKey] = errors[key];
    }

    return flattened;
}
