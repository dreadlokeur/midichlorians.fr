<table id="page" class="table table-bordered table-striped table-condensed datatable">
    <thead>
        <tr>
            <th></th>
            <th>Nom</th>
            <th>Titre</th>
            <th>Menu name</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (is_array($this->pages) && count($this->pages) > 0) { ?>
            <?php foreach ($this->pages as $page) { ?>
                <tr id="<?php echo $page->name; ?>">
                    <td name="id"><input type="checkbox" class="deleteCheckbox"></td>
                    <td><?php echo $page->name; ?></td>
                    <td class="editable" name="title"><?php echo $page->title; ?></td>
                    <td class="editable" name="menu"><?php echo $page->menu; ?></td>
                    <td>
                        <?php if ($page->deletable) { ?>
                            <a href="<?php echo $this->getUrl('pageDelete', array($page->name)); ?>" class="btn btn-default delete" title="Supprimer" alt="Supprimer"><i class="fa fa-times"></i></a>
                        <?php } ?>
                        <a href="<?php echo $this->getUrl('pageView', array($page->name)); ?>" class="btn btn-default ajax-switcher" title="Editer" alt="Editer"><i class="fa fa-pencil"></i></a>
                    </td>
                </tr>
            <?php } ?>
        <?php } ?>
    </tbody>
    <tfoot>
        <tr>
            <th></th>
            <th><input name="name" type="text" class="form-control" placeholder="Nom" required="required"></th>
            <th><input name="title" type="text" class="form-control" placeholder="Titre"></th>
            <th><input name="menu" type="text" class="form-control" placeholder="Menu"></th>
            <th>
                <a href="<?php echo $this->getUrl('pageAdd'); ?>" class="btn btn-default add" title="Ajouter" alt="Ajouter"><i class="fa fa-plus"></i></a>
            </th>
        </tr>
    </tfoot>
</table>