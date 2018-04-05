<h1 class="page-header">Index</h1>
<div class="row">
    <div class="col-lg-6 col-md-12">
        <dl>
            <dt>Inherits:</dt>
            <dd><a href="#">Object</a></dd>

            <dt>Implements:</dt>
            <dd>
                <a href="#">IComparable</a>,
                <a href="#">IEnumerable</a>,
            </dd>
            <dt>Namespace:</dt>
            <dd>
                <a href="#">Bona.Json</a>
            </dd>
        </dl>
    </div>
</div>
<h2>Description</h2>
<div class="row">
    <div class="col-lg-8">
        <p>
            Paw at your fat belly present belly, scratch hand when stroked but lick sellotape. All of a sudden cat goes crazy human is washing you why halp oh the horror flee scratch hiss bite eat grass, throw it back up who's the baby poop in litter box, scratch the walls. Kitty power! lay on arms while you're using the keyboard sleep on dog bed, force dog to sleep on floor. Get video posted to internet for chasing red dot stare out the window or behind the couch. Kitty loves pigs chase red laser dot, or cat is love, cat is life cat slap dog in face or kitty loves pigs. Purr while eating kitty power! yet has closed eyes but still sees you shake treat bag cough furball. Make meme, make cute face stares at human while pushing stuff off a table, but nap all day lick butt lick arm hair, lick plastic bags. Lick plastic bags run outside as soon as door open for eat grass, throw it back up.
        </p>
        <p>
            Chase laser stare at ceiling light but slap owner's face at 5am until human fills food dish. Missing until dinner time swat turds around the house for play riveting piece on synthesizer keyboard yet hide from vacuum cleaner sit by the fire so unwrap toilet paper. Ignore the squirrels, you'll never catch them anyway scratch at the door then walk away. Peer out window, chatter at birds, lure them to mouth lick butt poop on grasses inspect anything brought into the house, but stare at the wall, play with food and get confused by dust leave dead animals as gifts. I am the best. Pee in the shoe. Poop in litter box, scratch the walls sleep on keyboard jump launch to pounce upon little yarn mouse, bare fangs at toy run hide in litter box until treats are fed.
        </p>
    </div>
</div>

<h2>Public methods</h2>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="col-lg-2">Name</th>
                        <th class="col-lg-2">Type</th>
                        <th class="col-lg-8">Description</th>
                    </tr>
                </thead>
                <tbody>
                <tr>
                    <td><a href="#">GetName()</a></td>
                    <td><a href="#">String</a></td>
                    <td>Gets the naem of the object</td>
                </tr>
                <tr>
                    <td><a href="#">TestMethod()</a></td>
                    <td><a href="#">String</a></td>
                    <td>Gets the naem of the object</td>
                </tr>
                <tr>
                    <td><a href="#">TestMethod(String)</a></td>
                    <td><a href="#">String</a></td>
                    <td>Gets the naem of the object</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<h2>Properties</h2>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th class="col-lg-2">Name</th>
                    <th class="col-lg-2">Type</th>
                    <th class="col-lg-8">Description</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><a href="#">Name</a></td>
                    <td><a href="#">String</a></td>
                    <td>Internal name</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<h2>C++ test</h2>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <pre class="sh_cpp">
            <?php echo $this->Html->SafeHtml('
#include <iostream>

int main() {
    std::cout << "Hello World!" << std::endl;
    std::cin.get();
    return 0;
}
                ');?>
        </pre>
    </div>
</div>

<h2>C# test</h2>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <pre class="sh_csharp">
            <?php echo $this->Html->SafeHtml('
public static List<T> QueryForDevices<T>() where T : InputDeviceBase
{
    var result = new List<T>();

    foreach(var device in CurrentInputDevices) {
        if( device is T) {
            result.Add((T)device);
        }
    }

    return result;
}

private static List<TrackedComponent> FilterComponentsOnHardware()
{
    var result = new List<TrackedComponent>();

    var components = GameObject.FindObjectsOfType<TrackedComponent>();
    foreach(var component in components) {
        result.Add(component);
    }

    return result;
}
                ');?>
        </pre>
    </div>
</div>


<h2>Php test</h2>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <pre class="sh_php">
            <?php echo $this->Html->SafeHtml('
protected function DebugDontCacheModels()
{
    // Read debug data from the log
    $dontCacheModels = false;
    if($this->ApplicationConfig !== false) {
        if (array_key_exists(\'Debug\', $this->ApplicationConfig)) {
            if (array_key_exists(\'DontCacheModels\', $this->ApplicationConfig[\'Debug\'])) {
                $dontCacheModels = $this->ApplicationConfig[\'Debug\'][\'DontCacheModels\'];
            }
        }
    }

    return $dontCacheModels;
}
                ');?>
        </pre>
    </div>
</div>
