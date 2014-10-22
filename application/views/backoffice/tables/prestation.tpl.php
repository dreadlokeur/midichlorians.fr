<table id="prestation" class="table table-bordered table-striped table-condensed datatable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Content</th>
            <th>Icone (font awesome)</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (is_array($this->prestations) && count($this->prestations) > 0) { ?>
            <?php foreach ($this->prestations as $prestation) { ?>
                <tr id="<?php echo $prestation->id; ?>">
                    <td name="id">
                        <input type="checkbox" class="deleteCheckbox">
                        <?php echo $prestation->id; ?>
                    </td>
                    <td class="editable" name="content"><?php echo $prestation->content; ?></td>
                    <td class="editableSelect" name="icon">
                        <select class="icon-select" name="icon">
                            <?php if (is_array($this->icons) && count($this->icons) > 0) { ?>
                                <?php foreach ($this->icons as $icon) { ?>
                                    <option value="<?php echo $icon->iconId; ?>" <?php if ($prestation->icon == $icon->iconId) { ?> selected <?php } ?>>&amp;#x<?php echo $icon->iconUnicode; ?>; <?php echo $icon->iconName; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </td>
                    <td>
                        <a href="<?php echo $this->getUrl('prestationDelete', array($prestation->id)); ?>" class="btn btn-default delete" title="Supprimer" alt="Supprimer"><i class="fa fa-times"></i></a>
                    </td>
                </tr>
            <?php } ?>
        <?php } ?>
    </tbody>
    <tfoot>
        <tr>
            <th><input type="checkbox" name="selectAll" value="selectAll"></th>
            <th><input name="content" type="text" class="form-control" placeholder="Contenu" required="required"></th>
            <th>
                <select class="icon-select" name="icon">
                    <?php if (is_array($this->icons) && count($this->icons) > 0) { ?>
                        <?php foreach ($this->icons as $icon) { ?>
                            <option value="<?php echo $icon->iconId; ?>">&amp;#x<?php echo $icon->iconUnicode; ?>; <?php echo $icon->iconName; ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
            </th>
            <th>
                <a href="<?php echo $this->getUrl('prestationAdd'); ?>" class="btn btn-default add" title="Ajouter" alt="Ajouter"><i class="fa fa-plus"></i></a>
            </th>
        </tr>
    </tfoot>
</table>