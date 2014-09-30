<table id="media" class="table table-bordered table-striped table-condensed datatable">
    <thead>
        <tr>
            <th>
                <input type="checkbox" name="selectAll" value="selectAll"> 
                <span>ID</span>
            </th>
            <th>Fichier</th>
            <th>Type media</th>
            <th>Mime type</th>
            <th>Légende</th>
            <th>Texte alternatif</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (is_array($this->medias) && count($this->medias) > 0) { ?>
            <?php foreach ($this->medias as $media) { ?>
                <tr id="<?php echo $media->id; ?>">
                    <td name="id">
                        <input type="checkbox" value="" class="deleteCheckbox">
                        <?php echo $media->id; ?>
                    </td>
                    <td>
                        <?php if ($media->isImage()) { ?>
                            <img class="cursor-pointer" src="<?php echo $media->filename; ?>" title="Cliquer pour editer" alt="<?php echo $media->filename; ?>">
                        <?php } else { ?>
                            <img class="cursor-pointer" src="<?php echo $media->getThumbType(); ?>" title="Cliquer pour editer" alt="<?php echo $media->filename; ?>">
                        <?php } ?>
                    </td>
                    <td><?php echo $media->type; ?></td>
                    <td><?php echo $media->mime; ?></td>
                    <td class="editable" name="title"><?php echo $media->title; ?></td>
                    <td class="editable" name="alt"><?php echo $media->alt; ?></td>
                    <td>
                        <a href="<?php echo $media->filename; ?>" target="blanck"class="btn btn-default" title="Voir" alt="Voir"><i class="fa fa-eye"></i></a>
                        <a href="<?php echo $this->getUrl('mediaDelete', array($media->id)); ?>" class="btn btn-default delete" title="Supprimer" alt="Supprimer"><i class="fa fa-times"></i></a>
                    </td>
                </tr>
            <?php } ?>
        <?php } ?>
    </tbody>
</table>