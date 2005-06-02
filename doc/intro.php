<?php
require_once("docutil.php");
page_head("Overview of BOINC");
echo"
<p>
BOINC is a software platform for distributed computing
using volunteered computer resources.
<p>
<h2>Papers about BOINC</h2>
<p>
<a href=boinc2.pdf>Public Computing: Reconnecting People to Science<a>
<br>David P. Anderson.
<br>Conference on Shared Knowledge and the Web,
<br>Residencia de Estudiantes, Madrid, Spain, Nov. 17-19 2003.

<p>
This paper is about BOINC's design goals.
Also available in
<a href=madrid.html>HTML</a> |
<a href=http://boinc.de/madrid_de.htm>HTML/German</a> |
<a href=http://www.seti.nl/content.php?c=boinc_berkeley_madrid>HTML/Dutch</a>
<p>
<a href=grid_paper_04.pdf>BOINC: A System for Public-Resource Computing and Storage</a>
<br>David P. Anderson.
<br>5th IEEE/ACM International Workshop on Grid Computing,
<br>November 8, 2004, Pittsburgh, USA.
<p>
A more technical paper.


<h2>Features of BOINC</h2>
<p>
The BOINC's features fall into several areas:

<h3>Resource sharing among independent projects</h3>
<p>
Many different projects can use BOINC.
Projects are independent; each one operates its own servers and databases.
Participants can participate in multiple projects;
they control which projects they participate in,
and how their resources are divided among these projects.
When a project is down or has no work,
the resources of its participants are divided among other projects.
<p>

<h3>Project features</h3>
<p>
BOINC provides features that simplify
the creation and operation of distributed computing projects.
<ul>
<li>
<b>Flexible application framework</b>
<br>
Existing applications in common languages (C, C++, Fortran)
can run as BOINC applications with little or no modification.
An application can consist of several files
(e.g. multiple programs and a coordinating script).
New versions of applications can be deployed with no participant involvement.
<li>
<b>Security</b>
<br>
BOINC protects against several types of attacks.
For example, digital signatures based on public-key encryption
protect against the distribution of viruses.
<li>
<b>Multiple servers and fault-tolerance</b>
<br>
Projects can have separate scheduling and data servers,
with multiple servers of each type.
Clients automatically try alternate servers;
if all servers are down, clients do exponential backoff
to avoid flooding the servers when they come back up.
<li>
<b>Source code availability</b>
<br>
BOINC is distributed under the
<a href=http://www.gnu.org/copyleft/lesser.html>Lesser GNU Public License</a>.
However, BOINC applications need not be open source.
<li>
<b>Support for large data</b>
<br>
BOINC supports applications that produce or consume large amounts of data,
or that use large amounts of memory.
Data distribution and collection can be spread across many servers,
and participant hosts transfer large data unobtrusively.
Users can specify limits on disk usage and network bandwidth.
Work is dispatched only to hosts able to handle it.
</ul>

<h3>Participant features</h3>
<p>
BOINC provides the following features to participants:
<ul>
<li>
<b>Multiple participant platforms</b>
<br>
The BOINC core client is available for most common platforms (Mac OS X,
Windows, Linux and other Unix systems).
The client can use multiple CPUs.

<li>
<b>Web-based participant interfaces</b>
<br>
BOINC provides web-based interfaces for
account creation, preference editing, and participant status display.
A participant's preferences are automatically propagated to all their hosts,
making it easy to manage large numbers of hosts.

<li>
<b>Configurable host work caching</b>
<br>
The core client downloads enough work to keep its host
busy for a user-specifiable amount of time.
This can be used to decrease the frequency of connections or to
allow the host to keep working during project downtime.
</ul>
";
page_tail();
?>
