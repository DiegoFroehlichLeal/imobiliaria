<select class="form-select" name="bairro">
    <option value="">Selecione o bairro</option>
    <?php foreach ($bairros_por_cidade as $cidade => $bairros): ?>
        <optgroup label="<?= htmlspecialchars($cidade) ?>">
            <?php foreach ($bairros as $bairro): ?>
                <option value="<?= htmlspecialchars($bairro) ?>"><?= htmlspecialchars($bairro) ?></option>
            <?php endforeach; ?>
        </optgroup>
    <?php endforeach; ?>
</select>