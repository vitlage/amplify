<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Plugin;

class PluginController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->user()->admin->can('read', new \App\Model\Plugin())) {
            return $this->notAuthorized();
        }

        // If admin can view all sending domains
        if (!$request->user()->admin->can("readAll", new \App\Model\Plugin())) {
            $request->merge(array("admin_id" => $request->user()->admin->id));
        }

        // exlude customer seding plugins
        $request->merge(array("no_customer" => true));

        $plugins = \App\Model\Plugin::search($request);

        return view('admin.plugins.index', [
            'plugins' => $plugins
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request)
    {
        if (!$request->user()->admin->can('read', new \App\Model\Plugin())) {
            return $this->notAuthorized();
        }

        // If admin can view all sending domains
        if (!$request->user()->admin->can("readAll", new \App\Model\Plugin())) {
            $request->merge(array("admin_id" => $request->user()->admin->id));
        }

        // exlude customer seding plugins
        $request->merge(array("no_customer" => true));

        $plugins = \App\Model\Plugin::search($request)->paginate($request->per_page);

        $settingUrls = [];
        foreach ($plugins as $plugin) {
            // Generate setting buttons
            $composerJson = $plugin->getComposerJson($plugin->name);
            if (array_key_exists('extra', $composerJson) && array_key_exists('setting-route', $composerJson['extra'])) {
                $url = action($composerJson['extra']['setting-route']);
                $settingUrls[$plugin->name] = $url;
            }
        }

        return view('admin.plugins._list', [
            'plugins' => $plugins,
            'settingUrls' => $settingUrls
        ]);
    }

    /**
     * Install/Upgrage plugins.
     *
     * @return \Illuminate\Http\Response
     */
    public function install(Request $request)
    {
        // authorize
        if (!$request->user()->admin->can('install', Plugin::class)) {
            return $this->notAuthorized();
        }

        // do install
        if ($request->isMethod('post')) {
            // Upload
            $pluginName = Plugin::upload($request);

            // Install Plugin
            Plugin::installFromDir($pluginName);
        }

        return view('admin.plugins.install');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $items = \App\Model\Plugin::whereIn('uid', explode(',', $request->uids));

        foreach ($items->get() as $item) {
            // authorize
            if ($request->user()->admin->can('delete', $item)) {
                $item->delete();
            }
        }

        // Redirect to my lists page
        echo trans('messages.plugins.deleted');
    }

    /**
     * Disable sending server.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function disable(Request $request)
    {
        $items = \App\Model\Plugin::whereIn('uid', explode(',', $request->uids));

        foreach ($items->get() as $item) {
            // authorize
            if ($request->user()->admin->can('disable', $item)) {
                $item->disable();
            }
        }

        // Redirect to my lists page
        echo trans('messages.plugins.disabled');
    }

    /**
     * Disable sending server.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function enable(Request $request)
    {
        $items = \App\Model\Plugin::whereIn('uid', explode(',', $request->uids));

        foreach ($items->get() as $item) {
            // authorize
            if ($request->user()->admin->can('enable', $item)) {
                $item->activate();
            }
        }

        // Redirect to my lists page
        echo trans('messages.plugins.enabled');
    }

    /**
     * Email verification server display options form.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function options(Request $request, $uid=null)
    {
        if ($uid) {
            $plugin = \App\Model\Plugin::findByUid($uid);
        } else {
            $plugin = new \App\Model\Plugin($request->all());
            $options = $plugin->getOptions();
        }

        return view('admin.plugins._options', [
            'server' => $plugin,
            'options' => $options,
        ]);
    }
}
