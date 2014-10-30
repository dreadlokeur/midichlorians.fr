<table id="reference" class="table table-bordered table-striped table-condensed datatable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Date</th>
            <th>Lien</th>
            <th>Technologies</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (is_array($this->references) && count($this->references) > 0) { ?>
            <?php foreach ($this->references as $reference) { ?>
                <tr id="<?php echo $reference->id; ?>">
                    <td name="id"><input type="checkbox" class="deleteCheckbox"> <?php echo $reference->id; ?></td>
                    <td class="editable" name="name"><?php echo $reference->name; ?></td>
                    <td class="editableDate" name="date"><span class="hide"><?php echo $reference->date; ?></span><input name="date" type="text" class="form-control datepicker" value="<?php echo $reference->date; ?>"></td>
                    <td class="editable" name="link"><?php echo $reference->link; ?></td>
                    <td class="editable" name="technology"><?php echo $reference->technology; ?></td>
                    <td>
                        <a href="<?php echo $this->getUrl('referenceDelete', array($reference->id)); ?>" class="btn btn-default delete" title="Supprimer" alt="Supprimer"><i class="fa fa-times"></i></a>
                        <a href="<?php echo $this->getUrl('referenceView', array($reference->id)); ?>" class="btn btn-default ajax-switcher" title="Editer" alt="Editer"><i class="fa fa-pencil"></i></a>
                    </td>
                </tr>
            <?php } ?>
        <?php } ?>
    </tbody>
    <tfoot>
        <tr>
            <th></th>
            <th><input name="name" type="text" class="form-control" placeholder="Nom" required="required"></th>
            <th><input name="date" type="text" class="form-control datepicker" placeholder="Date"></th>
            <th><input name="link" type="text" class="form-control" placeholder="Lien"></th>
            <th><input name="technology" type="text" class="form-control" placeholder="Technologies"></th>
            <th>
                <a href="<?php echo $this->getUrl('referenceAdd'); ?>" class="btn btn-default add" title="Ajouter" alt="Ajouter"><i class="fa fa-plus"></i></a>
            </th>
        </tr>
    </tfoot>
</table>