<table id="media" class="table table-bordered table-striped table-condensed datatable">
    <thead>
        <tr>
            <th>ID</th>
            <th style="max-width: 100px;">Fichier</th>
            <th style="max-width: 100px;">Nom</th>
            <th>Type media</th>
            <th>Mime type</th>
            <th>Légende</th>
            <th>Texte alternatif</th>
            <th>Poids (byte)</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (is_array($this->medias) && count($this->medias) > 0) { ?>
            <?php foreach ($this->medias as $media) { ?>
                <tr id="<?php echo $media->id; ?>">
                    <td name="id"><input type="checkbox" class="deleteCheckbox"> <?php echo $media->id; ?></td>
                    <td>
                        <?php if ($media->isImage()) { ?>
                            <img class="cursor-pointer" src="<?php echo $media->filename; ?>" alt="<?php echo $media->filename; ?>">
                        <?php } else { ?>
                            <img class="cursor-pointer" src="<?php echo $media->getThumbType(); ?>" alt="<?php echo $media->filename; ?>">
                        <?php } ?>
                    </td>
                    <td><?php echo $media->getFilename(false); ?></td>
                    <td><?php echo $media->type; ?></td>
                    <td><?php echo $media->mime; ?></td>
                    <td class="editable" name="title"><?php echo $media->title; ?></td>
                    <td class="editable" name="alt"><?php echo $media->alt; ?></td>
                    <td><?php echo $media->size; ?></td>
                    <td>
                        <a href="<?php echo $media->filename; ?>" target="blanck"class="btn btn-default" title="Voir" alt="Voir"><i class="fa fa-eye"></i></a>
                        <a href="<?php echo $this->getUrl('mediaView', array($media->id)); ?>" class="btn btn-default ajax-switcher" title="Editer" alt="Editer"><i class="fa fa-pencil"></i></a>
                        <a href="<?php echo $this->getUrl('mediaDelete', array($media->id)); ?>" class="btn btn-default delete" title="Supprimer" alt="Supprimer"><i class="fa fa-times"></i></a>
                    </td>
                </tr>
            <?php } ?>
        <?php } ?>
    </tbody>
</table>