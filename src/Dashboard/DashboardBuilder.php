<?php namespace Anomaly\DashboardModule\Dashboard;

use Anomaly\DashboardModule\Dashboard\Command\BuildDashboard;
use Anomaly\DashboardModule\Dashboard\Command\LoadDashboard;
use Anomaly\Streams\Platform\Support\Collection;
use Illuminate\Foundation\Bus\DispatchesJobs;

/**
 * Class DashboardBuilder
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\DashboardModule\Dashboard
 */
class DashboardBuilder
{

    use DispatchesJobs;

    /**
     * The dashboard widgets.
     *
     * @var string|array
     */
    protected $widgets = 'Anomaly\DashboardModule\Dashboard\DashboardWidgets@handle';

    /**
     * The dashboard options.
     *
     * @var array
     */
    protected $options = [];

    /**
     * The dashboard object.
     *
     * @var Dashboard
     */
    protected $dashboard;

    /**
     * Create a new DashboardBuilder instance.
     *
     * @param Dashboard $dashboard
     */
    public function __construct(Dashboard $dashboard)
    {
        $this->dashboard = $dashboard;
    }

    /**
     * Build the dashboard.
     */
    public function build()
    {
        $this->dispatch(new BuildDashboard($this));
    }

    /**
     * Make the dashboard
     */
    public function make()
    {
        $this->build();

        $this->dispatch(new LoadDashboard($this->dashboard));

        $data = $this->dashboard->getData();

        $this->dashboard->setContent(view($this->dashboard->getOption('dashboard_view'), $data->all()));
    }

    /**
     * Render the dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $this->make();

        $content = $this->dashboard->getContent();

        return view(
            $this->dashboard->getOption(
                'wrapper_view',
                'streams::blank'
            ),
            compact('content')
        );
    }

    /**
     * Get the dashboard.
     *
     * @return Dashboard
     */
    public function getDashboard()
    {
        return $this->dashboard;
    }

    /**
     * Get the widgets.
     *
     * @return null|array|string
     */
    public function getWidgets()
    {
        return $this->widgets;
    }

    /**
     * Set the widgets.
     *
     * @param array $widgets
     * @return $this
     */
    public function setWidgets(array $widgets)
    {
        $this->widgets = $widgets;

        return $this;
    }

    /**
     * Get the options.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Get an option.
     *
     * @param      $key
     * @param null $default
     * @return mixed
     */
    public function getOption($key, $default = null)
    {
        return array_get($this->options, $key, $default);
    }

    /**
     * Set the options.
     *
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Set an option.
     *
     * @param $key
     * @param $value
     * @return $this
     */
    public function setOption($key, $value)
    {
        array_set($this->options, $key, $value);

        return $this;
    }

    /**
     * Get the options from the dashboard.
     *
     * @return Collection
     */
    public function getDashboardOptions()
    {
        return $this->dashboard->getOptions();
    }

    /**
     * Get an option value from the dashboard.
     *
     * @param      $key
     * @param null $default
     * @return mixed
     */
    public function getDashboardOption($key, $default = null)
    {
        return $this->dashboard->getOption($key, $default);
    }

    /**
     * Set an option value on the dashboard.
     *
     * @param $key
     * @param $value
     */
    public function setDashboardOption($key, $value)
    {
        $this->dashboard->setOption($key, $value);
    }

    /**
     * Add data to the dashboard.
     *
     * @param $key
     * @param $value
     * @return $this
     */
    public function addDashboardData($key, $value)
    {
        $this->dashboard->addData($key, $value);

        return $this;
    }
}
