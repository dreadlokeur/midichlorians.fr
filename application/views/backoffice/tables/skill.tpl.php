<table id="skill" class="table table-bordered table-striped table-condensed datatable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Valeur</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (is_array($this->skills) && count($this->skills) > 0) { ?>
            <?php foreach ($this->skills as $skill) { ?>
                <tr id="<?php echo $skill->id; ?>">
                    <td name="id">
                        <input type="checkbox" value="" class="deleteCheckbox">
                        <?php echo $skill->id; ?>
                    </td>
                    <td class="editable" name="name"><?php echo $skill->name; ?></td>
                    <td class="editable" name="value"><?php echo $skill->value; ?></td>
                    <td>
                        <a href="<?php echo $this->getUrl('skillDelete', array($skill->id)); ?>" class="btn btn-default delete" title="Supprimer" alt="Supprimer"><i class="fa fa-times"></i></a>
                    </td>
                </tr>
            <?php } ?>
        <?php } ?>
    </tbody>
    <tfoot>
        <tr>
            <th><input type="checkbox" name="selectAll" value="selectAll"></th>
            <th><input name="name" type="text" class="form-control" placeholder="Nom" required="required"></th>
            <th><input name="value" type="text" class="form-control" placeholder="Valeur"></th>
            <th>
                <a href="<?php echo $this->getUrl('skillAdd'); ?>" class="btn btn-default add" title="Ajouter" alt="Ajouter"><i class="fa fa-plus"></i></a>
            </th>
        </tr>
    </tfoot>
</table>