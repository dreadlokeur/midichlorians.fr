<table id="backlink" class="table table-bordered table-striped table-condensed datatable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Description</th>
            <th>Lien</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (is_array($this->backlinks) && count($this->backlinks) > 0) { ?>
            <?php foreach ($this->backlinks as $backlink) { ?>
                <tr id="<?php echo $backlink->id; ?>">
                    <td name="id">
                        <input type="checkbox" value="" class="deleteCheckbox">
                        <?php echo $backlink->id; ?>
                    </td>
                    <td class="editable" name="name"><?php echo $backlink->name; ?></td>
                    <td class="editable" name="descr"><?php echo $backlink->descr; ?></td>
                    <td class="editable" name="link"><?php echo $backlink->link; ?></td>
                    <td>
                        <a href="<?php echo $this->getUrl('backlinkDelete', array($backlink->id)); ?>" class="btn btn-default delete" title="Supprimer" alt="Supprimer"><i class="fa fa-times"></i></a>
                    </td>
                </tr>
            <?php } ?>
        <?php } ?>
    </tbody>
    <tfoot>
        <tr>
            <th><input type="checkbox" name="selectAll" value="selectAll"></th>
            <th><input name="name" type="text" class="form-control" placeholder="Nom" required="required"></th>
            <th><input name="descr" type="text" class="form-control" placeholder="Description"></th>
            <th><input name="link" type="text" class="form-control" placeholder="Lien" required="required"></th>
            <th>
                <a href="<?php echo $this->getUrl('backlinkAdd'); ?>" class="btn btn-default add" title="Ajouter" alt="Ajouter"><i class="fa fa-plus"></i></a>
            </th>
        </tr>
    </tfoot>
</table>