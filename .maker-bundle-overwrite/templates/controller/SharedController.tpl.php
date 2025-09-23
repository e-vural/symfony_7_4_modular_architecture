<?= "<?php\n" ?>

namespace <?= $class_data->getNamespace(); ?>;

<?= $class_data->getUseStatements(); ?>

<?= $class_data->getClassDeclaration(); ?>

<?php
$parent = $parent_class ?? 'AbstractController';
?>


{
// Shared base controller for module <?= htmlspecialchars($class_data->getClassName()) ?>

protected function jsonSuccess($data, int $status = 200): JsonResponse
{
return new JsonResponse($data, $status);
}

// Buraya tüm modül için ortak helper metodlarını ekleyebilirsin.
}
