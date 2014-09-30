<table id="cv" class="table table-bordered table-striped table-condensed datatable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Description</th>
            <th>Lien</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (is_array($this->cvs) && count($this->cvs) > 0) { ?>
            <?php foreach ($this->cvs as $cv) { ?>
                <tr id="<?php echo $cv->id; ?>">
                    <td name="id">
                        <input type="checkbox" value="" class="deleteCheckbox">
                        <?php echo $cv->id; ?>
                    </td>
                    <td class="editable" name="name"><?php echo $cv->name; ?></td>
                    <td class="editable" name="descr"><?php echo $cv->descr; ?></td>
                    <td class="editable" name="link"><?php echo $cv->link; ?></td>
                    <td class="cursor-pointer">
                        <img src="<?php echo $cv->media->filename; ?>">
                    </td>
                    <td>
                        <a href="<?php echo $this->getUrl('cvDelete', array($cv->id)); ?>" class="btn btn-default delete" title="Supprimer" alt="Supprimer"><i class="fa fa-times"></i></a>
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
            <th><input name="link" type="text" class="form-control" placeholder="Lien"></th>
            <th>
                <a href="" class="btn btn-default" title="Parcourir les medias" alt="Parcourir les medias"><i class="fa fa-folder-open"></i></a>
            </th>
            <th>
                <a href="<?php echo $this->getUrl('cvAdd'); ?>" class="btn btn-default add" title="Ajouter" alt="Ajouter"><i class="fa fa-plus"></i></a>
            </th>
        </tr>
    </tfoot>
</table>