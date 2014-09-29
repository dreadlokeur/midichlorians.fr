<table id="prestation" class="table table-bordered table-striped table-condensed datatable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Content</th>
            <th>Icon</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (is_array($this->prestations) && count($this->prestations) > 0) { ?>
            <?php foreach ($this->prestations as $prestation) { ?>
                <tr id="<?php echo $prestation->id; ?>">
                    <td name="id">
                        <input type="checkbox" value="" class="deleteCheckbox">
                        <?php echo $prestation->id; ?>
                    </td>
                    <td class="editable" name="content"><?php echo $prestation->content; ?></td>
                    <td class="editable" name="icon"><?php echo $prestation->icon; ?></td>
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
            <th><input name="icon" type="text" class="form-control" placeholder="Icon"></th>
            <th>
                <a href="<?php echo $this->getUrl('prestationAdd'); ?>" class="btn btn-default add" title="Ajouter" alt="Ajouter"><i class="fa fa-plus"></i></a>
            </th>
        </tr>
    </tfoot>
</table>