<?php
class BreadcrumbService {
    private array $items = [];
    private array $routeConfig;
    private $urlGenerator;

    public function __construct(array $routeConfig, callable $urlGenerator) {
        $this->routeConfig = $routeConfig;
        $this->urlGenerator = $urlGenerator;
        $this->addHome();
    }

    private function addHome(): void {
        $homeUrl = ($this->urlGenerator)('', '');
        $this->items[] = ['label' => 'Accueil', 'url' => $homeUrl];
    }

    public function buildFromRoute(string $controller, string $method, array $params = []): void {
        $config = $this->findRouteConfig($controller, $method);

        if ($config) {
            // Gérer le parent s'il existe
            if (isset($config['parent'])) {
                $this->resolveParentRoute($config['parent'], $params);
            }
            
            // Ajouter l'élément actuel
            $label = $this->resolveDynamicLabel($config['label'], $params);
            $url = ($this->urlGenerator)($controller, $method, $params);
            $this->addItem($label, $url);
        }
    }

    private function findRouteConfig(string $controller, string $method): ?array {
        return $this->routeConfig[$controller][$method] ?? null;
    }

    private function resolveParentRoute(string $parentRoute, array $params): void {
        // Cas spécial pour 'accueil' (déjà ajouté)
        if ($parentRoute === 'accueil') {
            return;
        }

        // Vérifier le format 'controller.method'
        if (!str_contains($parentRoute, '.')) {
            throw new \InvalidArgumentException("Format de route parente invalide : $parentRoute");
        }

        list($parentController, $parentMethod) = explode('.', $parentRoute, 2);
        $this->buildFromRoute($parentController, $parentMethod, $params);
    }

    private function resolveDynamicLabel(string $label, array $params): string {
        return preg_replace_callback('/:(\w+)/', function($matches) use ($params) {
            return $params[$matches[1]] ?? $matches[0];
        }, $label);
    }

    public function addItem(string $label, ?string $url = null): void {
        $this->items[] = ['label' => $label, 'url' => $url];
    }

    public function getItems(): array {
        return $this->items;
    }
}
?>